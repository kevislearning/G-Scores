<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>G-Scores</title>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #252ab0 0%, #202691 100%);
            min-height: 100vh;
            color: #333;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        /* HEADER */
        .header {
            text-align: center;
            padding: 40px 20px;
            color: white;
        }
        
        .header h1 {
            color: #f6da3d;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        
        .header p {
            color: #f6da3d;
            font-size: 1.3rem;
            opacity: 0.9;
            font-weight: 700;
        }
        
        /* NAVIGATION TABS */
        .nav-tabs {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        
        .nav-tab {
            background: rgba(255,255,255,0.2);
            border: none;
            padding: 12px 24px;
            border-radius: 30px;
            color: white;
            font-family: Arial, sans-serif;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .nav-tab:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-2px);
        }
        
        .nav-tab.active {
            background: white;
            color: #667eea;
        }
        
        /* CONTENT CARD */
        .content-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }
        
        .section-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .section-title::before {
            content: '';
            width: 4px; 
            height: 24px;
            background: linear-gradient(135deg, #667eea 0%, #1e248f 100%);
            border-radius: 2px;
        }
        
        /* SEARCH */
        .search-container {
            display: flex;
            gap: 15px;
            max-width: 500px;
            margin: 0 auto 30px;
        }
        
        .search-input {
            flex: 1;
            padding: 15px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-family: Arial, sans-serif;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }
        
        .search-input:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .search-btn {
            padding: 15px 30px;
            background: linear-gradient(135deg, #667eea 0%, #1e248f 100%);
            border: none;
            border-radius: 12px;
            color: white;
            font-family: Arial, sans-serif;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .search-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        
        /* DETAILED SCORES */
        .score-result {
            display: none;
        }
        
        .student-info {
            text-align: center;
            margin-bottom: 25px;
            padding: 20px;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            border-radius: 15px;
        }
        
        .student-sbd {
            font-size: 1.8rem;
            font-weight: 700;
            color: #667eea;
        }
        
        .student-language {
            font-size: 0.9rem;
            color: #666;
            margin-top: 5px;
        }
        
        .scores-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 15px;
        }
        
        .score-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 12px;
            text-align: center;
            transition: transform 0.3s ease;
        }
        
        .score-item:hover {
            transform: scale(1.05);
        }
        
        .score-subject {
            font-size: 0.85rem;
            color: #666;
            margin-bottom: 8px;
        }
        
        .score-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #333;
        }
        
        .score-value.excellent {
            color: #27ae60;
        }
        
        .score-value.good {
            color: #3498db;
        }
        
        .score-value.average {
            color: #f39c12;
        }
        
        .score-value.poor {
            color: #e74c3c;
        }
        
        /* CHART */
        .chart-container {
            position: relative;
            height: 400px;
            margin: 20px 0;
        }
        
        .chart-select {
            padding: 10px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-family: Arial, sans-serif;
            font-size: 1rem;
            margin-bottom: 20px;
            cursor: pointer;
        }
        
        /* TOP STUDENTS */
        .top-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .top-table th,
        .top-table td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .top-table th {
            background: linear-gradient(135deg, #1e248f 0%, #667eea 100%);
            color: white;
            font-weight: 500;
        }
        
        .top-table tr:hover {
            background: #f8f9fa;
        }
        
        .rank-badge {
            display: inline-block;
            width: 30px;
            height: 30px;
            line-height: 30px;
            border-radius: 50%;
            font-weight: 600;
        }
        
        .rank-1 { background: #ffd700; color: #333; }
        .rank-2 { background: #c0c0c0; color: #333; }
        .rank-3 { background: #cd7f32; color: white; }
        
        /* HIDDEN */
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        /* ERROR/SUCCESS MESSAGE */
        .message {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .message.error {
            background: #fee2e2;
            color: #dc2626;
        }
        
        .message.success {
            background: #d1fae5;
            color: #059669;
        }
        
        /* LOADING SPINNER */
        .loading {
            display: none;
            text-align: center;
            padding: 40px;
        }
        
        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* RESPONSIVE */
        @media (max-width: 768px) {
            .header h1 {
                font-size: 1.8rem;
            }
            
            .search-container {
                flex-direction: column;
            }
            
            .scores-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .top-table {
                font-size: 0.85rem;
            }
            
            .top-table th,
            .top-table td {
                padding: 10px 5px;
            }
        }

        /* CHART LEGEND */
        .chart-legend {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 15px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
        }

        .legend-color {
            width: 16px;
            height: 16px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <header class="header">
            <h1>🎓 G-Scores</h1>
            <p>Hệ thống tra cứu điểm thi THPT Quốc gia 2024</p>
        </header>
        
        <!-- Navigation tabs -->
        <nav class="nav-tabs">
            <button class="nav-tab active" data-tab="search">🔍 Tra cứu điểm</button>
            <button class="nav-tab" data-tab="statistics">📊 Thống kê</button>
            <button class="nav-tab" data-tab="top10">🏆 Top 10 khối A</button>
        </nav>
        
        <!-- Search tab -->
        <div id="search" class="tab-content active">
            <div class="content-card">
                <h2 class="section-title">Tra cứu điểm theo số báo danh</h2>
                
                <div class="search-container">
                    <input type="text" class="search-input" id="sbd-input" 
                           placeholder="Nhập số báo danh (8 chữ số)" 
                           maxlength="8" pattern="[0-9]{8}">
                    <button class="search-btn" id="search-btn">Tra cứu</button>
                </div>
                
                <div id="search-message" class="message" style="display: none;"></div>
                
                <div class="loading" id="search-loading">
                    <div class="spinner"></div>
                    <p>Đang tìm kiếm...</p>
                </div>
                
                <div class="score-result" id="score-result">
                    <div class="student-info">
                        <div class="student-sbd">SBD: <span id="result-sbd"></span></div>
                        <div class="student-language">Mã ngoại ngữ: <span id="result-language"></span></div>
                    </div>
                    <div class="scores-grid" id="scores-grid"></div>
                </div>
            </div>
        </div>
        
        <!-- Statistics tab -->
        <div id="statistics" class="tab-content">
            <div class="content-card">
                <h2 class="section-title">Thống kê điểm theo môn học</h2>
                
                <select class="chart-select" id="subject-select">
                    <option value="all">Tất cả môn</option>
                    <option value="toan">Toán</option>
                    <option value="ngu_van">Ngữ văn</option>
                    <option value="ngoai_ngu">Ngoại ngữ</option>
                    <option value="vat_li">Vật lý</option>
                    <option value="hoa_hoc">Hóa học</option>
                    <option value="sinh_hoc">Sinh học</option>
                    <option value="lich_su">Lịch sử</option>
                    <option value="dia_li">Địa lý</option>
                    <option value="gdcd">GDCD</option>
                </select>
                
                <div class="loading" id="stats-loading">
                    <div class="spinner"></div>
                    <p>Đang tải dữ liệu...</p>
                </div>
                
                <div class="chart-container">
                    <canvas id="statsChart"></canvas>
                </div>
                
                <div class="chart-legend">
                    <div class="legend-item">
                        <div class="legend-color" style="background: #27ae60;"></div>
                        <span>Giỏi (>= 8)</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background: #3498db;"></div>
                        <span>Khá (6-8)</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background: #f39c12;"></div>
                        <span>Trung bình (4-6)</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background: #e74c3c;"></div>
                        <span>Yếu (< 4)</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Top 10 tab -->
        <div id="top10" class="tab-content">
            <div class="content-card">
                <h2 class="section-title">Top 10 thí sinh điểm cao nhất khối A</h2>
                <p style="color: #666; margin-bottom: 20px;">Tổ hợp khối A: Toán + Vật lý + Hóa học</p>
                
                <div class="loading" id="top10-loading">
                    <div class="spinner"></div>
                    <p>Đang tải dữ liệu...</p>
                </div>
                
                <div id="top10-table-container">
                    <table class="top-table" id="top10-table">
                        <thead>
                            <tr>
                                <th>Hạng</th>
                                <th>Số báo danh</th>
                                <th>Toán</th>
                                <th>Vật lý</th>
                                <th>Hóa học</th>
                                <th>Tổng điểm</th>
                            </tr>
                        </thead>
                        <tbody id="top10-body"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        $(document).ready(function() {
            // CSRF token setup
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            let statsChart = null;
            let statisticsData = null;
            
            // Tab navigation
            $('.nav-tab').click(function() {
                const tab = $(this).data('tab');
                
                $('.nav-tab').removeClass('active');
                $(this).addClass('active');
                
                $('.tab-content').removeClass('active');
                $('#' + tab).addClass('active');
                
                // Load data for some tabs
                if (tab === 'statistics' && !statisticsData) {
                    loadStatistics();
                } else if (tab === 'top10' && $('#top10-body').is(':empty')) {
                    loadTop10();
                }
            });
            
            // Search function
            $('#search-btn').click(function() {
                searchScore();
            });
            
            $('#sbd-input').keypress(function(e) {
                if (e.which === 13) {
                    searchScore();
                }
            });
            
            // Only numbers in SBD input check
            $('#sbd-input').on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
            
            function searchScore() {
                const sbd = $('#sbd-input').val().trim();
                
                if (!sbd) {
                    showMessage('Vui lòng nhập số báo danh', 'error');
                    return;
                }
                
                if (!/^[0-9]{8}$/.test(sbd)) {
                    showMessage('Số báo danh phải có đúng 8 chữ số', 'error');
                    return;
                }
                
                $('#search-message').hide();
                $('#score-result').hide();
                $('#search-loading').show();
                
                $.ajax({
                    url: '/api/search',
                    method: 'POST',
                    data: { sbd: sbd },
                    success: function(response) {
                        $('#search-loading').hide();
                        
                        if (response.success) {
                            displayScore(response.data);
                        } else {
                            showMessage(response.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        $('#search-loading').hide();
                        const message = xhr.responseJSON?.message || 'Có lỗi xảy ra, vui lòng thử lại';
                        showMessage(message, 'error');
                    }
                });
            }
            
            function showMessage(text, type) {
                $('#search-message')
                    .removeClass('error success')
                    .addClass(type)
                    .text(text)
                    .show();
            }
            
            function displayScore(data) {
                $('#result-sbd').text(data.sbd);
                $('#result-language').text(data.ma_ngoai_ngu || 'Không có');
                
                let html = '';
                data.scores.forEach(function(item) {
                    let scoreClass = '';
                    if (item.score !== null) {
                        if (item.score >= 8) scoreClass = 'excellent';
                        else if (item.score >= 6) scoreClass = 'good';
                        else if (item.score >= 4) scoreClass = 'average';
                        else scoreClass = 'poor';
                    }
                    
                    html += `
                        <div class="score-item">
                            <div class="score-subject">${item.subject}</div>
                            <div class="score-value ${scoreClass}">${item.display}</div>
                        </div>
                    `;
                });
                
                $('#scores-grid').html(html);
                $('#score-result').show();
            }
            
            // Statistics function
            function loadStatistics() {
                $('#stats-loading').show();
                
                $.ajax({
                    url: '/api/statistics',
                    method: 'GET',
                    success: function(response) {
                        $('#stats-loading').hide();
                        
                        if (response.success) {
                            statisticsData = response.data;
                            renderChart('all');
                        }
                    },
                    error: function() {
                        $('#stats-loading').hide();
                        alert('Có lỗi xảy ra khi tải dữ liệu');
                    }
                });
            }
            
            $('#subject-select').change(function() {
                const subject = $(this).val();
                renderChart(subject);
            });
            
            function renderChart(subject) {
                if (!statisticsData) return;
                
                const ctx = document.getElementById('statsChart').getContext('2d');
                
                if (statsChart) {
                    statsChart.destroy();
                }
                
                let labels = [];
                let excellentData = [];
                let goodData = [];
                let averageData = [];
                let poorData = [];
                
                if (subject === 'all') {
                    Object.keys(statisticsData).forEach(function(key) {
                        labels.push(statisticsData[key].name);
                        excellentData.push(statisticsData[key].stats.excellent);
                        goodData.push(statisticsData[key].stats.good);
                        averageData.push(statisticsData[key].stats.average);
                        poorData.push(statisticsData[key].stats.poor);
                    });
                } else {
                    const data = statisticsData[subject];
                    labels = ['Giỏi (>= 8)', 'Khá (6-8)', 'TB (4-6)', 'Yếu (< 4)'];
                    excellentData = [data.stats.excellent];
                    goodData = [data.stats.good];
                    averageData = [data.stats.average];
                    poorData = [data.stats.poor];
                }
                
                if (subject === 'all') {
                    statsChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [
                                {
                                    label: 'Giỏi (>= 8)',
                                    data: excellentData,
                                    backgroundColor: '#27ae60',
                                },
                                {
                                    label: 'Khá (6-8)',
                                    data: goodData,
                                    backgroundColor: '#3498db',
                                },
                                {
                                    label: 'Trung bình (4-6)',
                                    data: averageData,
                                    backgroundColor: '#f39c12',
                                },
                                {
                                    label: 'Yếu (< 4)',
                                    data: poorData,
                                    backgroundColor: '#e74c3c',
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function(value) {
                                            return value.toLocaleString();
                                        }
                                    }
                                }
                            }
                        }
                    });
                } else {
                    const data = statisticsData[subject];
                    statsChart = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: ['Giỏi (>= 8)', 'Khá (6-8)', 'Trung bình (4-6)', 'Yếu (< 4)'],
                            datasets: [{
                                data: [data.stats.excellent, data.stats.good, data.stats.average, data.stats.poor],
                                backgroundColor: ['#27ae60', '#3498db', '#f39c12', '#e74c3c'],
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom'
                                }
                            }
                        }
                    });
                }
            }
            
            // Top 10 function
            function loadTop10() {
                $('#top10-loading').show();
                
                $.ajax({
                    url: '/api/top-group-a',
                    method: 'GET',
                    success: function(response) {
                        $('#top10-loading').hide();
                        
                        if (response.success) {
                            displayTop10(response.data);
                        }
                    },
                    error: function() {
                        $('#top10-loading').hide();
                        alert('Có lỗi xảy ra khi tải dữ liệu');
                    }
                });
            }
            
            function displayTop10(data) {
                let html = '';
                
                data.forEach(function(student) {
                    let rankClass = '';
                    if (student.rank <= 3) {
                        rankClass = 'rank-' + student.rank;
                    }
                    
                    html += `
                        <tr>
                            <td><span class="rank-badge ${rankClass}">${student.rank}</span></td>
                            <td><strong>${student.sbd}</strong></td>
                            <td>${student.toan.toFixed(2)}</td>
                            <td>${student.vat_li.toFixed(2)}</td>
                            <td>${student.hoa_hoc.toFixed(2)}</td>
                            <td><strong>${student.total.toFixed(2)}</strong></td>
                        </tr>
                    `;
                });
                
                $('#top10-body').html(html);
            }
        });
    </script>
</body>
</html>
