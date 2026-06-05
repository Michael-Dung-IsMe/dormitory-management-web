<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hóa đơn tiền phòng KTX - Phòng <?php echo htmlspecialchars($this->bill->room_number); ?></title>
    <style>
        body { font-family: 'Times New Roman', serif; line-height: 1.5; color: #000; background: #fff; padding: 0; margin: 0; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, 0.15); font-size: 16px; }
        .header { text-align: center; margin-bottom: 40px; border-bottom: 2px solid #000; padding-bottom: 15px; }
        .header h1 { margin: 0; font-size: 24px; text-transform: uppercase; }
        .header p { margin: 5px 0 0; font-style: italic; }
        .info-table { width: 100%; margin-bottom: 30px; }
        .info-table td { padding: 5px; }
        .details-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .details-table th, .details-table td { border: 1px solid #000; padding: 10px; text-align: right; }
        .details-table th { background-color: #f2f2f2; text-align: center; font-weight: bold; }
        .details-table td.text-left { text-align: left; }
        .details-table td.text-center { text-align: center; }
        .total-row { font-weight: bold; font-size: 18px; }
        .footer { display: flex; justify-content: space-between; margin-top: 50px; text-align: center; }
        .footer-col { width: 45%; }
        .signature-space { height: 100px; }
        .status-badge { display: inline-block; padding: 5px 15px; border: 2px solid; border-radius: 5px; font-weight: bold; margin-bottom: 20px; font-size: 20px; }
        .status-paid { color: #28a745; border-color: #28a745; }
        .status-unpaid { color: #dc3545; border-color: #dc3545; }
        @media print {
            .invoice-box { box-shadow: none; border: none; margin: 0; padding: 0; }
            button { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="invoice-box">
        <div class="header">
            <h1>BAN QUẢN LÝ KÝ TÚC XÁ</h1>
            <p>HÓA ĐƠN ĐIỆN, NƯỚC VÀ PHÍ DỊCH VỤ</p>
        </div>

        <?php
            $is_paid = $this->bill->status == 'Đã thanh toán';
        ?>
        <div style="text-align: center;">
            <div class="status-badge <?php echo $is_paid ? 'status-paid' : 'status-unpaid'; ?>">
                <?php echo $is_paid ? 'ĐÃ THANH TOÁN' : 'CHƯA THANH TOÁN'; ?>
            </div>
        </div>

        <table class="info-table">
            <tr>
                <td width="20%"><strong>Phòng:</strong></td>
                <td width="30%">Phòng <?php echo htmlspecialchars($this->bill->room_number); ?></td>
                <td width="20%"><strong>Mã hóa đơn:</strong></td>
                <td width="30%">#<?php echo str_pad($this->bill->bill_id, 6, '0', STR_PAD_LEFT); ?></td>
            </tr>
            <tr>
                <td><strong>Kỳ thu:</strong></td>
                <td>Tháng <?php echo $this->bill->billing_month; ?> / <?php echo $this->bill->billing_year; ?></td>
                <td><strong>Ngày lập:</strong></td>
                <td><?php echo date('d/m/Y', strtotime($this->bill->created_date)); ?></td>
            </tr>
        </table>

        <?php
            $elec_usage = $this->bill->new_electric_index - $this->bill->old_electric_index;
            $water_usage = $this->bill->new_water_index - $this->bill->old_water_index;
            $elec_fee = $elec_usage * 3500;
            $water_fee = $water_usage * 10000;
        ?>

        <table class="details-table">
            <thead>
                <tr>
                    <th width="5%">STT</th>
                    <th width="35%">Nội dung</th>
                    <th width="15%">Số lượng</th>
                    <th width="20%">Đơn giá (VNĐ)</th>
                    <th width="25%">Thành tiền (VNĐ)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">1</td>
                    <td class="text-left">
                        Tiền điện sinh hoạt<br>
                        <small><i>(Từ số: <?php echo $this->bill->old_electric_index; ?> đến số: <?php echo $this->bill->new_electric_index; ?>)</i></small>
                    </td>
                    <td class="text-center"><?php echo $elec_usage; ?> KWh</td>
                    <td>3.500</td>
                    <td><?php echo number_format($elec_fee, 0, ',', '.'); ?></td>
                </tr>
                <tr>
                    <td class="text-center">2</td>
                    <td class="text-left">
                        Tiền nước sinh hoạt<br>
                        <small><i>(Từ số: <?php echo $this->bill->old_water_index; ?> đến số: <?php echo $this->bill->new_water_index; ?>)</i></small>
                    </td>
                    <td class="text-center"><?php echo $water_usage; ?> Khối</td>
                    <td>10.000</td>
                    <td><?php echo number_format($water_fee, 0, ',', '.'); ?></td>
                </tr>
                <?php if($this->bill->room_fee > 0): ?>
                <tr>
                    <td class="text-center">3</td>
                    <td class="text-left">Phí thuê phòng / Khác</td>
                    <td class="text-center">1 Tháng</td>
                    <td><?php echo number_format($this->bill->room_fee, 0, ',', '.'); ?></td>
                    <td><?php echo number_format($this->bill->room_fee, 0, ',', '.'); ?></td>
                </tr>
                <?php endif; ?>
                <tr class="total-row">
                    <td colspan="4">TỔNG CỘNG PHẢI THANH TOÁN:</td>
                    <td><?php echo number_format($this->bill->total_amount, 0, ',', '.'); ?> VNĐ</td>
                </tr>
            </tbody>
        </table>

        <div class="footer">
            <div class="footer-col">
                <strong>Đại diện phòng</strong>
                <div class="signature-space"></div>
                <i>(Ký và ghi rõ họ tên)</i>
            </div>
            <div class="footer-col">
                <strong>Người lập phiếu</strong>
                <div class="signature-space"></div>
                <i>(Ký và ghi rõ họ tên)</i>
            </div>
        </div>
    </div>
</body>
</html>
