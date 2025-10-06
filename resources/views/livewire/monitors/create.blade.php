<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
        <form wire:submit="save" class="space-y-6">
            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Monitor Name</label>
                <input type="text" wire:model="name" id="name" class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Type -->
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Monitor Type</label>
                <select wire:model.live="type" id="type" class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    <option value="http_ping">HTTP Ping</option>
                    <option value="heartbeat">Heartbeat</option>
                    <option value="ssl_certificate">SSL Certificate</option>
                </select>
                @error('type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            @if($type === 'http_ping' || $type === 'ssl_certificate')
                <!-- URL -->
                <div>
                    <label for="url" class="block text-sm font-medium text-gray-700 dark:text-gray-300">URL</label>
                    <input type="url" wire:model="url" id="url" placeholder="https://example.com" class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    @error('url') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                @if($type === 'http_ping')
                    <!-- Method -->
                    <div>
                        <label for="method" class="block text-sm font-medium text-gray-700 dark:text-gray-300">HTTP Method</label>
                        <select wire:model="method" id="method" class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="GET">GET</option>
                            <option value="POST">POST</option>
                            <option value="PUT">PUT</option>
                            <option value="PATCH">PATCH</option>
                            <option value="DELETE">DELETE</option>
                            <option value="HEAD">HEAD</option>
                        </select>
                        @error('method') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Expected Status Codes -->
                    <div>
                        <label for="expected_status_codes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Expected Status Codes (comma-separated)</label>
                        <input type="text" wire:model="expected_status_codes" id="expected_status_codes" placeholder="200,201,204" class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Default: 200</p>
                        @error('expected_status_codes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Keyword -->
                    <div>
                        <label for="keyword" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Keyword to Check (optional)</label>
                        <input type="text" wire:model="keyword" id="keyword" class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        @error('keyword') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    @if($keyword)
                        <!-- Keyword Present -->
                        <div class="flex items-center">
                            <input type="checkbox" wire:model="keyword_present" id="keyword_present" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="keyword_present" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                Keyword should be present (uncheck if keyword should NOT be present)
                            </label>
                        </div>
                    @endif
                @endif
            @endif

            @if($type === 'heartbeat')
                <!-- Expected Heartbeat Interval -->
                <div>
                    <label for="expected_heartbeat" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Expected Heartbeat Interval (seconds)</label>
                    <input type="number" wire:model="expected_heartbeat" id="expected_heartbeat" min="60" class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">How often should we expect a heartbeat?</p>
                    @error('expected_heartbeat') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Grace Period -->
                <div>
                    <label for="grace_period" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Grace Period (seconds)</label>
                    <input type="number" wire:model="grace_period" id="grace_period" min="0" class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Additional time before marking as down</p>
                    @error('grace_period') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="bg-blue-50 dark:bg-blue-900 p-4 rounded-md">
                    <h4 class="text-sm font-medium text-blue-800 dark:text-blue-200 mb-2">Heartbeat URL:</h4>
                    <p class="text-sm text-blue-700 dark:text-blue-300 font-mono break-all">
                        POST {{ url('/') }}/heartbeat/{monitor-id}
                    </p>
                    <p class="text-xs text-blue-600 dark:text-blue-400 mt-2">
                        Send a POST request to this URL from your application to signal it's alive.
                    </p>
                </div>
            @endif

            <!-- Check Interval -->
            <div>
                <label for="check_interval" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Check Interval (seconds)</label>
                <input type="number" wire:model="check_interval" id="check_interval" min="30" class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">How often to check (minimum: 30 seconds)</p>
                @error('check_interval') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Timeout -->
            <div>
                <label for="timeout" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Timeout (seconds)</label>
                <input type="number" wire:model="timeout" id="timeout" min="1" max="60" class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                @error('timeout') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('monitors.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-500">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    Create Monitor
                </button>
            </div>
        </form>
    </div>
</div>
