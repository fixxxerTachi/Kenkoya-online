					<?php if($row->delivery_date == '0000-00-00 00:00:00'):?>
						<?php $delivery_date = '���t�w��Ȃ�' ?>
					<?php else:?>	
						<?php $delivery_date = new DateTime($row->delivery_date) ?>
						<?php $delivery_date = $delivery_date->format('Y/m/d'); ?>
					<?php endif;?>
							<td>�z�B�\���:<br> <?php echo $delivery_date ?><br><?php echo $takuhai_hours[$row->delivery_hour] ?></td>
							<td>
								<?php echo $order_status[$row->status_flag] ?>
							</td>
					<?php if(!$order->csv_flag == 1): ?>
							<td><a class='edit' href='<?php echo site_url("/mypage/change_quantity/{$row->order_detail_id}/") ?>'>���ʕύX</a></td>
							<td><a class='edit' href='<?php echo site_url("/mypage/cancel/{$row->order_detail_id}/") ?>'>�����L�����Z��</a></td>
					<?php endif;?>
					
												<td class='product'>
								<?php echo $row->product_name ?><br>���i�P���F<?php echo number_format($row->sale_price) ?>�~<br>�������F<?php echo $row->quantity ?>
							</td>
