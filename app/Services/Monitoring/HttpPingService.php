<?php

namespace App\Services\Monitoring;

use App\Models\MonitorCheck;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;

class HttpPingService extends MonitoringService
{
    public function check(): MonitorCheck
    {
        $startTime = microtime(true);

        try {
            $client = new Client([
                'timeout' => $this->monitor->timeout,
                'http_errors' => false,
            ]);

            $options = [
                'headers' => $this->monitor->headers ?? [],
            ];

            if ($this->monitor->body) {
                $options['body'] = $this->monitor->body;
            }

            $response = $client->request(
                $this->monitor->method,
                $this->monitor->url,
                $options
            );

            $responseTime = (int) ((microtime(true) - $startTime) * 1000);
            $statusCode = $response->getStatusCode();

            $expectedCodes = $this->monitor->expected_status_codes ?? [200, 201, 202, 204];
            $isStatusValid = in_array($statusCode, $expectedCodes);

            $body = (string) $response->getBody();
            $isKeywordValid = $this->checkKeyword($body);

            $status = ($isStatusValid && $isKeywordValid) ? 'up' : 'down';

            return $this->recordCheck($status, [
                'response_time' => $responseTime,
                'status_code' => $statusCode,
                'response_headers' => $response->getHeaders(),
                'error_message' => !$isStatusValid 
                    ? "Unexpected status code: {$statusCode}" 
                    : (!$isKeywordValid ? 'Keyword check failed' : null),
            ]);

        } catch (ConnectException $e) {
            return $this->recordCheck('down', [
                'error_message' => 'Connection timeout or failed: ' . $e->getMessage(),
            ]);
        } catch (RequestException $e) {
            return $this->recordCheck('down', [
                'error_message' => 'Request failed: ' . $e->getMessage(),
                'status_code' => $e->getResponse() ? $e->getResponse()->getStatusCode() : null,
            ]);
        } catch (\Exception $e) {
            return $this->recordCheck('down', [
                'error_message' => 'Unexpected error: ' . $e->getMessage(),
            ]);
        }
    }

    protected function checkKeyword(?string $body): bool
    {
        if (empty($this->monitor->keyword)) {
            return true;
        }

        $keywordPresent = str_contains($body, $this->monitor->keyword);

        return $this->monitor->keyword_present ? $keywordPresent : !$keywordPresent;
    }
}
