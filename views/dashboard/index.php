<?php
$pageTitle = 'Tổng quan';
include 'views/layout/header.php';

// Chuyển PHP array sang JS array
$monthlyRevenueJson = json_encode(array_values($monthlyRevenue));
?>

<div class="stat-cards">
    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="fa-solid fa-users"></i>
        </div>
        <div class="stat-details">
            <h3><?php echo htmlspecialchars($stats['total_students']); ?></h3>
            <p>Tổng sinh viên</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon green">
            <i class="fa-solid fa-door-open"></i>
        </div>
        <div class="stat-details">
            <h3><?php echo htmlspecialchars($stats['available_rooms']); ?></h3>
            <p>Phòng trống</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon red">
            <i class="fa-solid fa-file-invoice-dollar"></i>
        </div>
        <div class="stat-details">
            <h3><?php echo htmlspecialchars($stats['unpaid_bills']); ?></h3>
            <p>Hóa đơn chưa thu</p>
        </div>
    </div>
</div>

<div class="card">
    <h3 class="card-title">Doanh thu năm <?php echo date('Y'); ?> (VNĐ)</h3>
    <canvas id="myChart" height="100"></canvas>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('myChart').getContext('2d');
        const revenueData = <?php echo $monthlyRevenueJson; ?>;
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
                datasets: [{
                    label: 'Doanh thu đã thu',
                    data: revenueData,
                    backgroundColor: 'rgba(67, 97, 238, 0.5)',
                    borderColor: 'rgba(67, 97, 238, 1)',
                    borderWidth: 1,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('vi-VN').format(value) + ' ₫';
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('vi-VN').format(context.parsed.y) + ' VNĐ';
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    });
</script>

<?php include 'views/layout/footer.php'; ?>
