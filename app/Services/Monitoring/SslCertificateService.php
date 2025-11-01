<?php

namespace App\Services\Monitoring;

use App\Models\MonitorCheck;

class SslCertificateService extends MonitoringService
{
    public function check(): MonitorCheck
    {
        try {
            $url = parse_url($this->monitor->url);
            $host = $url['host'] ?? $this->monitor->url;
            $port = $url['port'] ?? 443;

            $context = stream_context_create([
                'ssl' => [
                    'capture_peer_cert' => true,
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ],
            ]);

            $client = @stream_socket_client(
                "ssl://{$host}:{$port}",
                $errno,
                $errstr,
                $this->monitor->timeout,
                STREAM_CLIENT_CONNECT,
                $context
            );

            if (!$client) {
                return $this->recordCheck('down', [
                    'error_message' => "Failed to connect: {$errstr}",
                ]);
            }

            $params = stream_context_get_params($client);
            $cert = openssl_x509_parse($params['options']['ssl']['peer_certificate']);

            fclose($client);

            if (!$cert) {
                return $this->recordCheck('down', [
                    'error_message' => 'Failed to parse SSL certificate',
                ]);
            }

            $expiryDate = $cert['validTo_time_t'];
            $daysUntilExpiry = (int) (($expiryDate - time()) / 86400);

            $certInfo = [
                'subject' => $cert['subject']['CN'] ?? 'Unknown',
                'issuer' => $cert['issuer']['CN'] ?? 'Unknown',
                'valid_from' => date('Y-m-d H:i:s', $cert['validFrom_time_t']),
                'valid_to' => date('Y-m-d H:i:s', $cert['validTo_time_t']),
                'days_until_expiry' => $daysUntilExpiry,
            ];

            // Certificate expires in less than 30 days - consider it down
            if ($daysUntilExpiry < 30) {
                return $this->recordCheck('down', [
                    'ssl_certificate_info' => $certInfo,
                    'error_message' => "SSL certificate expires in {$daysUntilExpiry} days",
                ]);
            }

            return $this->recordCheck('up', [
                'ssl_certificate_info' => $certInfo,
            ]);

        } catch (\Exception $e) {
            return $this->recordCheck('down', [
                'error_message' => 'SSL check failed: ' . $e->getMessage(),
            ]);
        }
    }
}
