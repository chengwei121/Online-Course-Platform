<!-- Inline Loading Spinner Component -->
<div class="inline-loader" style="display: none;" x-data="{ show: false }" x-show="show" x-cloak>
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl p-8 shadow-2xl max-w-sm w-full mx-4 text-center">
            <div class="relative w-16 h-16 mx-auto mb-4">
                <div class="absolute inset-0 border-4 border-gray-200 rounded-full"></div>
                <div class="absolute inset-0 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
                <div class="absolute inset-4 bg-gradient-to-r from-primary to-secondary rounded-full flex items-center justify-center">
                    <i class="fas fa-graduation-cap text-white text-sm"></i>
                </div>
            </div>
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Loading...</h3>
            <p class="text-gray-600 text-sm">Please wait while we prepare your content</p>
            <div class="mt-4">
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-gradient-to-r from-primary to-secondary h-2 rounded-full animate-pulse" style="width: 60%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Content Loading State -->
<div class="content-loader" style="display: none;">
    <div class="animate-pulse">
        <div class="h-8 bg-gray-200 rounded-lg mb-4"></div>
        <div class="space-y-3">
            <div class="h-4 bg-gray-200 rounded w-3/4"></div>
            <div class="h-4 bg-gray-200 rounded w-1/2"></div>
            <div class="h-4 bg-gray-200 rounded w-5/6"></div>
        </div>
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-gray-200 rounded-lg h-48"></div>
            <div class="bg-gray-200 rounded-lg h-48"></div>
            <div class="bg-gray-200 rounded-lg h-48"></div>
        </div>
    </div>
</div>

<!-- Button Loading State Component -->
<script>
    function showButtonLoading(button, text = 'Loading...') {
        const originalText = button.innerHTML;
        button.disabled = true;
        button.innerHTML = `<i class="fas fa-spinner fa-spin me-2"></i>${text}`;
        
        return function hideLoading() {
            button.disabled = false;
            button.innerHTML = originalText;
        };
    }

    function showInlineLoader() {
        document.querySelector('.inline-loader').style.display = 'flex';
        document.querySelector('.inline-loader').__x.$data.show = true;
    }

    function hideInlineLoader() {
        document.querySelector('.inline-loader').__x.$data.show = false;
        setTimeout(() => {
            document.querySelector('.inline-loader').style.display = 'none';
        }, 300);
    }

    function showContentLoader() {
        document.querySelector('.content-loader').style.display = 'block';
    }

    function hideContentLoader() {
        document.querySelector('.content-loader').style.display = 'none';
    }
</script>