<?php
/**
 * NEPSE Sidebar Template Part
 * Nepal Stock Exchange Market Data Sidebar
 * 
 * @package Samachar_Patra
 * @since 1.0
 */
?>

<div class="nepse-sidebar">
    <!-- NEPSE Main Index -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title mb-0">
                <i class="fas fa-chart-line"></i> नेप्से सूचकांक
            </h5>
            <small>Nepal Stock Exchange</small>
        </div>
        <div class="card-body text-center">
            <div class="nepse-main-index" id="nepse-main-index">
                <div class="index-value mb-2">
                    <span class="h2 text-primary" id="nepse-value">
                        <div class="spinner-border" role="status"></div>
                    </span>
                </div>
                <div class="index-change" id="nepse-change">
                    <small class="text-muted">लोड हुँदैछ...</small>
                </div>
                <div class="index-details mt-3">
                    <div class="row">
                        <div class="col-6">
                            <div class="detail-item">
                                <div class="detail-value text-success" id="day-high">-</div>
                                <small class="detail-label">दिनको उच्च</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="detail-item">
                                <div class="detail-value text-danger" id="day-low">-</div>
                                <small class="detail-label">दिनको न्यून</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-center bg-light">
            <small class="text-muted">
                <i class="fas fa-clock"></i> 
                अन्तिम अपडेट: <span id="nepse-last-update"></span>
            </small>
        </div>
    </div>

    <!-- Market Overview -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="card-title mb-0">
                <i class="fas fa-chart-bar"></i> बजार अवलोकन
            </h5>
        </div>
        <div class="card-body p-2">
            <div class="market-overview-grid">
                <div class="overview-item">
                    <div class="d-flex justify-content-between">
                        <span class="item-label">कुल कारोबार:</span>
                        <span class="item-value text-primary" id="total-turnover">-</span>
                    </div>
                </div>
                <div class="overview-item">
                    <div class="d-flex justify-content-between">
                        <span class="item-label">कुल मात्रा:</span>
                        <span class="item-value text-info" id="total-volume">-</span>
                    </div>
                </div>
                <div class="overview-item">
                    <div class="d-flex justify-content-between">
                        <span class="item-label">वृद्धि भएका:</span>
                        <span class="item-value text-success" id="advancing-stocks">-</span>
                    </div>
                </div>
                <div class="overview-item">
                    <div class="d-flex justify-content-between">
                        <span class="item-label">गिरावट भएका:</span>
                        <span class="item-value text-danger" id="declining-stocks">-</span>
                    </div>
                </div>
                <div class="overview-item">
                    <div class="d-flex justify-content-between">
                        <span class="item-label">अपरिवर्तित:</span>
                        <span class="item-value text-secondary" id="unchanged-stocks">-</span>
                    </div>
                </div>
                <div class="overview-item">
                    <div class="d-flex justify-content-between">
                        <span class="item-label">कारोबार भएका:</span>
                        <span class="item-value text-dark" id="traded-stocks">-</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Gainers/Losers -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-success text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-trophy"></i> शीर्ष प्रदर्शनकर्ता
                </h5>
                <div class="btn-group btn-group-sm" role="group">
                    <input type="radio" class="btn-check" name="stock-display" id="gainers-radio" checked>
                    <label class="btn btn-outline-light btn-sm" for="gainers-radio">वृद्धि</label>
                    
                    <input type="radio" class="btn-check" name="stock-display" id="losers-radio">
                    <label class="btn btn-outline-light btn-sm" for="losers-radio">गिरावट</label>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="stock-list" id="gainers-list">
                <!-- Top gainers will be loaded here -->
            </div>
            <div class="stock-list d-none" id="losers-list">
                <!-- Top losers will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Sector Performance -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-warning text-dark">
            <h5 class="card-title mb-0">
                <i class="fas fa-industry"></i> क्षेत्रगत प्रदर्शन
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="sectors-performance" id="sectors-performance">
                <!-- Sector performance will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Market Indices -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-secondary text-white">
            <h5 class="card-title mb-0">
                <i class="fas fa-list"></i> अन्य सूचकांकहरू
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="indices-list" id="other-indices">
                <!-- Other indices will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Market News -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white">
            <h5 class="card-title mb-0">
                <i class="fas fa-newspaper"></i> बजार समाचार
            </h5>
        </div>
        <div class="card-body p-2" id="market-news">
            <!-- Market news will be loaded here -->
            <div class="text-center p-3">
                <div class="spinner-border spinner-border-sm" role="status">
                    <span class="visually-hidden">समाचार लोड हुँदैछ...</span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.nepse-sidebar .card {
    border: none;
    border-radius: 10px;
}

.nepse-sidebar .card-header {
    border-radius: 10px 10px 0 0;
    padding: 0.75rem 1rem;
}

.index-value {
    font-family: 'Arial', sans-serif;
}

.index-change {
    font-size: 1.1em;
}

.detail-item {
    text-align: center;
    padding: 0.5rem;
}

.detail-value {
    font-weight: bold;
    font-size: 1.1em;
}

.detail-label {
    display: block;
    color: #6c757d;
    font-size: 0.8em;
}

.market-overview-grid .overview-item {
    padding: 0.5rem 0.75rem;
    border-bottom: 1px solid #f0f0f0;
}

.market-overview-grid .overview-item:last-child {
    border-bottom: none;
}

.item-label {
    font-size: 0.9em;
    color: #495057;
}

.item-value {
    font-weight: bold;
    font-size: 0.9em;
}

.stock-list-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem;
    border-bottom: 1px solid #f0f0f0;
    transition: background-color 0.2s;
}

.stock-list-item:hover {
    background-color: #f8f9fa;
}

.stock-list-item:last-child {
    border-bottom: none;
}

.stock-symbol {
    font-weight: bold;
    color: #495057;
    font-size: 0.9em;
}

.stock-price-info {
    text-align: right;
}

.stock-price {
    font-weight: bold;
    font-size: 0.9em;
}

.stock-change {
    font-size: 0.8em;
}

.stock-change.positive {
    color: #28a745;
}

.stock-change.negative {
    color: #dc3545;
}

.sector-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.6rem 0.75rem;
    border-bottom: 1px solid #f0f0f0;
}

.sector-item:last-child {
    border-bottom: none;
}

.sector-name {
    font-size: 0.85em;
    color: #495057;
}

.sector-change {
    font-size: 0.8em;
    font-weight: bold;
}

.indices-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.6rem 0.75rem;
    border-bottom: 1px solid #f0f0f0;
}

.indices-item:last-child {
    border-bottom: none;
}

.index-name {
    font-size: 0.85em;
    font-weight: 500;
}

.index-value-small {
    font-size: 0.8em;
    font-weight: bold;
}

.news-item {
    padding: 0.6rem 0.75rem;
    border-bottom: 1px solid #f0f0f0;
}

.news-item:last-child {
    border-bottom: none;
}

.news-title {
    font-size: 0.85em;
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
    margin-top: 0.25rem;
}

.btn-group .btn-sm {
    font-size: 0.7em;
    padding: 0.25rem 0.5rem;
}

@media (max-width: 768px) {
    .nepse-sidebar {
        margin-top: 2rem;
    }
    
    .nepse-sidebar .card-header h5 {
        font-size: 1rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadNEPSEData();
    setupStockToggle();
    loadMarketNews();
    
    // Refresh data every 5 minutes
    setInterval(loadNEPSEData, 300000);
});

function loadNEPSEData() {
    // Mock NEPSE data (replace with actual API calls in production)
    const nepseData = {
        index: {
            value: (Math.random() * 500 + 2000).toFixed(2),
            change: (Math.random() * 40 - 20).toFixed(2),
            changePercent: (Math.random() * 2 - 1).toFixed(2),
            dayHigh: (Math.random() * 50 + 2050).toFixed(2),
            dayLow: (Math.random() * 50 + 1950).toFixed(2)
        },
        marketOverview: {
            totalTurnover: (Math.random() * 5000 + 3000).toFixed(2) + ' करोड',
            totalVolume: Math.floor(Math.random() * 1000000 + 500000).toLocaleString(),
            advancingStocks: Math.floor(Math.random() * 50 + 30),
            decliningStocks: Math.floor(Math.random() * 40 + 20),
            unchangedStocks: Math.floor(Math.random() * 20 + 10),
            tradedStocks: Math.floor(Math.random() * 100 + 150)
        },
        topGainers: [
            { symbol: 'NABIL', price: 1205.00, change: '+2.5', changePercent: '+0.21' },
            { symbol: 'SCB', price: 425.00, change: '+8.0', changePercent: '+1.92' },
            { symbol: 'EBL', price: 612.00, change: '+15.0', changePercent: '+2.51' },
            { symbol: 'BOKL', price: 289.50, change: '+6.5', changePercent: '+2.30' },
            { symbol: 'NIC', price: 875.00, change: '+12.0', changePercent: '+1.39' }
        ],
        topLosers: [
            { symbol: 'UPPER', price: 645.00, change: '-15.0', changePercent: '-2.27' },
            { symbol: 'CHCL', price: 389.00, change: '-8.0', changePercent: '-2.01' },
            { symbol: 'KBL', price: 245.50, change: '-5.5', changePercent: '-2.19' },
            { symbol: 'CCBL', price: 198.00, change: '-4.0', changePercent: '-1.98' },
            { symbol: 'MLBL', price: 456.00, change: '-9.0', changePercent: '-1.94' }
        ],
        sectors: [
            { name: 'बैंकिङ', change: '+0.45', class: 'text-success' },
            { name: 'विकास बैंक', change: '-0.23', class: 'text-danger' },
            { name: 'फाइनान्स', change: '+1.12', class: 'text-success' },
            { name: 'बीमा', change: '-0.67', class: 'text-danger' },
            { name: 'जलविद्युत', change: '+2.34', class: 'text-success' },
            { name: 'होटल', change: '-1.45', class: 'text-danger' },
            { name: 'उत्पादन', change: '+0.89', class: 'text-success' },
            { name: 'व्यापार', change: '-0.34', class: 'text-danger' }
        ],
        otherIndices: [
            { name: 'Sensitive Index', value: '385.67', change: '+1.23', class: 'text-success' },
            { name: 'Float Index', value: '156.89', change: '-0.45', class: 'text-danger' },
            { name: 'Banking Index', value: '1456.78', change: '+2.34', class: 'text-success' }
        ]
    };

    // Update main index
    document.getElementById('nepse-value').textContent = nepseData.index.value;
    
    const changeElement = document.getElementById('nepse-change');
    const changeValue = parseFloat(nepseData.index.change);
    const changeClass = changeValue >= 0 ? 'text-success' : 'text-danger';
    const changeIcon = changeValue >= 0 ? '↑' : '↓';
    
    changeElement.innerHTML = `
        <span class="${changeClass}">
            ${changeIcon} ${Math.abs(nepseData.index.change)} (${nepseData.index.changePercent}%)
        </span>
    `;

    document.getElementById('day-high').textContent = nepseData.index.dayHigh;
    document.getElementById('day-low').textContent = nepseData.index.dayLow;

    // Update market overview
    document.getElementById('total-turnover').textContent = nepseData.marketOverview.totalTurnover;
    document.getElementById('total-volume').textContent = nepseData.marketOverview.totalVolume;
    document.getElementById('advancing-stocks').textContent = nepseData.marketOverview.advancingStocks;
    document.getElementById('declining-stocks').textContent = nepseData.marketOverview.decliningStocks;
    document.getElementById('unchanged-stocks').textContent = nepseData.marketOverview.unchangedStocks;
    document.getElementById('traded-stocks').textContent = nepseData.marketOverview.tradedStocks;

    // Update top gainers/losers
    displayStockList('gainers-list', nepseData.topGainers, 'positive');
    displayStockList('losers-list', nepseData.topLosers, 'negative');

    // Update sectors
    displaySectors(nepseData.sectors);

    // Update other indices
    displayOtherIndices(nepseData.otherIndices);

    // Update timestamp
    document.getElementById('nepse-last-update').textContent = new Date().toLocaleTimeString('ne-NP');
}

function displayStockList(containerId, stocks, type) {
    const container = document.getElementById(containerId);
    if (!container) return;

    let html = '';
    stocks.forEach(stock => {
        const changeClass = type === 'positive' ? 'positive' : 'negative';
        html += `
            <div class="stock-list-item">
                <div>
                    <div class="stock-symbol">${stock.symbol}</div>
                </div>
                <div class="stock-price-info">
                    <div class="stock-price">₹ ${stock.price}</div>
                    <div class="stock-change ${changeClass}">${stock.change} (${stock.changePercent}%)</div>
                </div>
            </div>
        `;
    });
    container.innerHTML = html;
}

function displaySectors(sectors) {
    const container = document.getElementById('sectors-performance');
    if (!container) return;

    let html = '';
    sectors.forEach(sector => {
        html += `
            <div class="sector-item">
                <div class="sector-name">${sector.name}</div>
                <div class="sector-change ${sector.class}">${sector.change}%</div>
            </div>
        `;
    });
    container.innerHTML = html;
}

function displayOtherIndices(indices) {
    const container = document.getElementById('other-indices');
    if (!container) return;

    let html = '';
    indices.forEach(index => {
        html += `
            <div class="indices-item">
                <div class="index-name">${index.name}</div>
                <div class="text-end">
                    <div class="index-value-small">${index.value}</div>
                    <div class="sector-change ${index.class}">${index.change}%</div>
                </div>
            </div>
        `;
    });
    container.innerHTML = html;
}

function setupStockToggle() {
    const gainersRadio = document.getElementById('gainers-radio');
    const losersRadio = document.getElementById('losers-radio');
    const gainersList = document.getElementById('gainers-list');
    const losersList = document.getElementById('losers-list');

    if (gainersRadio && losersRadio && gainersList && losersList) {
        gainersRadio.addEventListener('change', function() {
            if (this.checked) {
                gainersList.classList.remove('d-none');
                losersList.classList.add('d-none');
            }
        });

        losersRadio.addEventListener('change', function() {
            if (this.checked) {
                losersList.classList.remove('d-none');
                gainersList.classList.add('d-none');
            }
        });
    }
}

function loadMarketNews() {
    const newsContainer = document.getElementById('market-news');
    
    // Mock market news data
    const newsItems = [
        { title: 'नेप्से सूचकांकमा उल्लेखनीय वृद्धि', time: '२ घण्टा अगाडि' },
        { title: 'बैंकिङ्ग सेक्टरमा सकारात्मक प्रवृत्ति', time: '४ घण्टा अगाडि' },
        { title: 'नयाँ कम्पनीको शेयर निष्कासन', time: '६ घण्टा अगाडि' },
        { title: 'जलविद्युत शेयरमा बढी चासो', time: '८ घण्टा अगाडि' }
    ];

    let html = '';
    newsItems.forEach(item => {
        html += `
            <div class="news-item">
                <a href="#" class="news-title d-block">${item.title}</a>
                <div class="news-time">${item.time}</div>
            </div>
        `;
    });

    newsContainer.innerHTML = html;
}
</script>