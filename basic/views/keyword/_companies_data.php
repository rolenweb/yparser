<?php
use yii\helpers\Html;
?>
<div class="table-responsive">
  	<table class="table table-bordered">
  		<tbody>
  			<?php foreach ($data->companies as $company): ?>
				<tr>
					<th rowspan="11"><?= $company->title ?></th>
					<th>Yp ID</th>
					<td><?= $company->ypid ?></td>
					
				</tr>    		
				<tr>
					<th>Address</th>
					<td><?= $company->address ?></td>
				</tr>
				<tr>
					<th>Phone</th>
					<td><?= $company->phone ?></td>
				</tr>
				<tr>
					<th>Lat</th>
					<td><?= $company->lat ?></td>
				</tr>
				<tr>
					<th>Lon</th>
					<td><?= $company->lon ?></td>
				</tr>
				<tr>
					<th>Hours</th>
					<td>
						<?php if (empty($company->hours) === false): ?>
							<?php $hours = $company->formatHours(); ?>
							<table class="table">
								<tbody>
									<?php if (empty($hours['regular']) === false): ?>
										<tr>
											<?= Html::beginTag('th',['colspan' => count($hours['regular'])]) ?>
												Regular
											<?= Html::endTag('th') ?>
										</tr>
										<?php foreach ($hours['regular'] as $day): ?>
											<tr>
												<td>
													<ul>
														<li><?= $day['start'] ?></li>
														<li><?= $day['end'] ?></li>
													</ul>
												</td>
											</tr>
										<?php endforeach ?>
									<?php endif ?>
									<?php if (empty($hours['delivery']) === false): ?>
										<tr>
											<?= Html::beginTag('th',['colspan' => count($hours['regular'])]) ?>
												Delivery
											<?= Html::endTag('th') ?>
										</tr>
										<?php foreach ($hours['delivery'] as $day): ?>
											<tr>
												<td>
													<ul>
														<li><?= $day['start'] ?></li>
														<li><?= $day['end'] ?></li>
													</ul>
												</td>
											</tr>
										<?php endforeach ?>
									<?php endif ?>
								</tbody>
							</table>
						<?php endif ?>
					</td>
				</tr>
				<tr>
					<th>Categories</th>
					<td>
						<?php if (empty($company->categories) === false): ?>
							<ul>
								<?php foreach ($company->categories as $category): ?>
									<li><?= $category->title ?></li>
								<?php endforeach ?>
							</ul>
						<?php endif ?>
					</td>
				</tr>
				<tr>
					<th>Extra phones</th>
					<td>
						<?php if (empty($company->extraPhones) === false): ?>
							<ul>
								<?php foreach ($company->extraPhones as $phone): ?>
									<li><?= $phone->phone ?></li>
								<?php endforeach ?>
							</ul>
						<?php endif ?>
					</td>
				</tr>
				<tr>
					<th>Paymet Methods</th>
					<td>
						<?php if (empty($company->paymetMethods) === false): ?>
							<ul>
								<?php foreach ($company->paymetMethods as $method): ?>
									<li><?= $method->method ?></li>
								<?php endforeach ?>
							</ul>
						<?php endif ?>
					</td>
				</tr>
				<tr>
					<th>Weblinks</th>
					<td>
						<?php if (empty($company->weblinks) === false): ?>
							<ul>
								<?php foreach ($company->weblinks as $link): ?>
									<li><?= $link->link ?></li>
								<?php endforeach ?>
							</ul>
						<?php endif ?>
					</td>
				</tr>
				<tr>
					<th>Info</th>
					<td>
						<?php if (empty($company->info) === false): ?>
							
								<?php foreach ($company->info as $info): ?>
									<div><?= $info->info ?></div>
								<?php endforeach ?>
							
						<?php endif ?>
					</td>
				</tr>
    		<?php endforeach ?>	
  		</tbody>
  	</table>
</div>