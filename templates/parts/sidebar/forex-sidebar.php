<?php
/**
 * Forex Sidebar Template Part
 * Dynamic forex rates sidebar for Nepali currency
 * 
 * @package Samachar_Patra
 * @since 1.0
 */
?>

<div class="forex-sidebar">
    <!-- Quick Forex Rates Widget -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title mb-0">
                <i class="fas fa-chart-line"></i> आजको दर
            </h5>
            <small>तुरुन्त अपडेट</small>
        </div>
        <div class="card-body p-0">
            <div class="forex-widget" id="forex-widget">
                <!-- Quick rates will be loaded here -->
                <div class="text-center p-3">
                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                        <span class="visually-hidden">लोड हुँदैछ...</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-center">
            <small class="text-muted">
                <i class="fas fa-clock"></i> 
                अन्तिम अपडेट: <span id="sidebar-last-updated"></span>
            </small>
        </div>
    </div>

    <!-- Currency Calculator Widget -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="card-title mb-0">
                <i class="fas fa-calculator"></i> मुद्रा गणना
            </h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label small">रकम</label>
                <input type="number" class="form-control form-control-sm" id="sidebar-amount" value="1" min="0" step="0.01">
            </div>
            <div class="mb-3">
                <label class="form-label small">मुद्रा</label>
                <select class="form-select form-select-sm" id="sidebar-currency">
                    <option value="USD">USD - अमेरिकी डलर</option>
                    <option value="EUR">EUR - युरो</option>
                    <option value="GBP">GBP - बेलायती पाउण्ड</option>
                    <option value="INR">INR - भारतीय रुपैयाँ</option>
                    <option value="CNY">CNY - चिनियाँ युआन</option>
                    <option value="JPY">JPY - जापानी येन</option>
                    <option value="AUD">AUD - अस्ट्रेलियाली डलर</option>
                </select>
            </div>
            <div class="result-display p-2 bg-light rounded">
                <small class="text-muted">नेपाली रुपैयाँमा:</small>
                <div class="h5 text-primary mb-0" id="sidebar-result">₹ 0.00</div>
            </div>
        </div>
    </div>

    <!-- Trending Currencies -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-warning text-dark">
            <h5 class="card-title mb-0">
                <i class="fas fa-trending-up"></i> ट्रेन्डिङ मुद्रा
            </h5>
        </div>
        <div class="card-body p-2">
            <div id="trending-currencies">
                <!-- Trending currencies will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Gold & Silver Rates -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-secondary text-white">
            <h5 class="card-title mb-0">
                <i class="fas fa-coins"></i> सुनचाँदीको दर
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-6">
                    <div class="text-center">
                        <div class="h6 text-warning">
                            <i class="fas fa-circle text-warning"></i> सुन
                        </div>
                        <div class="small" id="gold-rate">₹ --</div>
                        <small class="text-muted">प्रति तोला</small>
                    </div>
                </div>
                <div class="col-6">
                    <div class="text-center">
                        <div class="h6 text-secondary">
                            <i class="fas fa-circle text-secondary"></i> चाँदी
                        </div>
                        <div class="small" id="silver-rate">₹ --</div>
                        <small class="text-muted">प्रति तोला</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Market Status -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="card-title mb-0">
                <i class="fas fa-globe"></i> बजार स्थिति
            </h5>
        </div>
        <div class="card-body">
            <div class="market-status" id="market-status">
                <div class="mb-2">
                    <div class="d-flex justify-content-between">
                        <span class="small">नेप्से:</span>
                        <span class="text-success small">
                            <i class="fas fa-arrow-up"></i> +1.25%
                        </span>
                    </div>
                </div>
                <div class="mb-2">
                    <div class="d-flex justify-content-between">
                        <span class="small">भारतीय रुपैयाँ:</span>
                        <span class="text-danger small">
                            <i class="fas fa-arrow-down"></i> -0.15%
                        </span>
                    </div>
                </div>
                <div class="mb-2">
                    <div class="d-flex justify-content-between">
                        <span class="small">अमेरिकी डलर:</span>
                        <span class="text-success small">
                            <i class="fas fa-arrow-up"></i> +0.08%
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Economic News Widget -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white">
            <h5 class="card-title mb-0">
                <i class="fas fa-newspaper"></i> आर्थिक समाचार
            </h5>
        </div>
        <div class="card-body p-2" id="economic-news">
            <!-- Economic news will be loaded here -->
            <div class="text-center p-2">
                <div class="spinner-border spinner-border-sm" role="status">
                    <span class="visually-hidden">समाचार लोड हुँदैछ...</span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.forex-sidebar .card {
    border: none;
    border-radius: 8px;
}

.forex-sidebar .card-header {
    border-radius: 8px 8px 0 0;
    padding: 0.75rem 1rem;
}

.forex-widget {
    max-height: 300px;
    overflow-y: auto;
}

.currency-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 1rem;
    border-bottom: 1px solid #f0f0f0;
    transition: background-color 0.2s;
}

.currency-item:hover {
    background-color: #f8f9fa;
}

.currency-item:last-child {
    border-bottom: none;
}

.currency-symbol {
    font-weight: bold;
    color: #495057;
}

.currency-name {
    font-size: 0.85em;
    color: #6c757d;
}

.currency-rate {
    text-align: right;
}

.rate-value {
    font-weight: bold;
    color: #007bff;
}

.rate-change {
    font-size: 0.8em;
}

.rate-change.positive {
    color: #28a745;
}

.rate-change.negative {
    color: #dc3545;
}

.trending-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem;
    margin-bottom: 0.5rem;
    background: #f8f9fa;
    border-radius: 4px;
}

.market-status .small {
    font-size: 0.85em;
}

.economic-news-item {
    padding: 0.5rem;
    border-bottom: 1px solid #f0f0f0;
}

.economic-news-item:last-child {
    border-bottom: none;
}

.news-title {
    font-size: 0.9em;
    font-weight: 500;
    color: #333;
    text-decoration: none;
    line-height: 1.3;
}

.news-title:hover {
    color: #007bff;
}

.news-time {
    font-size: 0.75em;
    color: #6c757d;
}

.result-display {
    border: 1px solid #e9ecef;
}

@media (max-width: 768px) {
    .forex-sidebar {
        margin-top: 2rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadSidebarForexRates();
    setupSidebarCalculator();
    loadTrendingCurrencies();
    loadGoldSilverRates();
    loadEconomicNews();
    
    // Refresh sidebar data every 2 minutes
    setInterval(function() {
        loadSidebarForexRates();
        loadTrendingCurrencies();
    }, 120000);
});

function loadSidebarForexRates() {
    const apiUrl = 'https://api.exchangerate-api.com/v4/latest/NPR';
    
    fetch(apiUrl)
        .then(response => response.json())
        .then(data => {
            displaySidebarRates(data);
            document.getElementById('sidebar-last-updated').textContent = new Date().toLocaleString('ne-NP');
        })
        .catch(error => {
            console.error('Error fetching sidebar forex rates:', error);
            document.getElementById('forex-widget').innerHTML = `
                <div class="text-center p-3 text-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <small>डेटा लोड गर्न सकिएन</small>
                </div>
            `;
        });
}

function displaySidebarRates(data) {
    const widget = document.getElementById('forex-widget');
    const mainCurrencies = ['USD', 'EUR', 'GBP', 'INR', 'CNY'];
    
    let html = '';
    
    mainCurrencies.forEach(code => {
        if (data.rates[code]) {
            const rate = (1 / data.rates[code]).toFixed(4);
            const change = (Math.random() * 2 - 1).toFixed(2); // Simulated change
            const changeClass = change >= 0 ? 'positive' : 'negative';
            const changeIcon = change >= 0 ? '↑' : '↓';
            
            const currencyNames = {
                'USD': 'अमेरिकी डलर',
                'EUR': 'युरो',
                'GBP': 'बेलायती पाउण्ड',
                'INR': 'भारतीय रुपैयाँ',
                'CNY': 'चिनियाँ युआन'
            };
            
            html += `
                <div class="currency-item">
                    <div>
                        <div class="currency-symbol">${code}</div>
                        <div class="currency-name">${currencyNames[code]}</div>
                    </div>
                    <div class="currency-rate">
                        <div class="rate-value">₹ ${rate}</div>
                        <div class="rate-change ${changeClass}">
                            ${changeIcon} ${Math.abs(change)}%
                        </div>
                    </div>
                </div>
            `;
        }
    });
    
    widget.innerHTML = html;
}

function setupSidebarCalculator() {
    const amountInput = document.getElementById('sidebar-amount');
    const currencySelect = document.getElementById('sidebar-currency');
    const resultDiv = document.getElementById('sidebar-result');
    
    function calculate() {
        const amount = parseFloat(amountInput.value) || 0;
        const currency = currencySelect.value;
        
        if (amount > 0) {
            fetch(`https://api.exchangerate-api.com/v4/latest/${currency}`)
                .then(response => response.json())
                .then(data => {
                    const rate = data.rates.NPR || 1;
                    const result = (amount * rate).toFixed(2);
                    resultDiv.textContent = `₹ ${result}`;
                })
                .catch(error => {
                    resultDiv.textContent = '₹ Error';
                });
        } else {
            resultDiv.textContent = '₹ 0.00';
        }
    }
    
    amountInput.addEventListener('input', calculate);
    currencySelect.addEventListener('change', calculate);
    
    // Initial calculation
    calculate();
}

function loadTrendingCurrencies() {
    const trendingDiv = document.getElementById('trending-currencies');
    
    // Simulated trending data (in real implementation, use actual trending API)
    const trendingData = [
        { symbol: 'USD', change: '+0.25%', class: 'text-success' },
        { symbol: 'EUR', change: '-0.15%', class: 'text-danger' },
        { symbol: 'GBP', change: '+0.08%', class: 'text-success' },
        { symbol: 'INR', change: '-0.05%', class: 'text-danger' }
    ];
    
    let html = '';
    trendingData.forEach(item => {
        html += `
            <div class="trending-item">
                <span class="fw-bold">${item.symbol}</span>
                <span class="${item.class}">${item.change}</span>
            </div>
        `;
    });
    
    trendingDiv.innerHTML = html;
}

function loadGoldSilverRates() {
    // Simulated gold/silver rates (use actual precious metals API)
    const goldRate = (Math.random() * 10000 + 90000).toFixed(0);
    const silverRate = (Math.random() * 1000 + 1200).toFixed(0);
    
    document.getElementById('gold-rate').textContent = `₹ ${goldRate}`;
    document.getElementById('silver-rate').textContent = `₹ ${silverRate}`;
}

function loadEconomicNews() {
    const newsDiv = document.getElementById('economic-news');
    
    // Simulated economic news (use actual news API)
    const newsItems = [
        { title: 'नेप्से सूचकांकमा वृद्धि', time: '२ घण्टा अगाडि' },
        { title: 'विदेशी मुद्रा सञ्चितिमा सुधार', time: '४ घण्टा अगाडि' },
        { title: 'मुद्रास्फीति दर स्थिर', time: '६ घण्टा अगाडि' }
    ];
    
    let html = '';
    newsItems.forEach(item => {
        html += `
            <div class="economic-news-item">
                <a href="#" class="news-title d-block">${item.title}</a>
                <div class="news-time">${item.time}</div>
            </div>
        `;
    });
    
    newsDiv.innerHTML = html;
}
</script>