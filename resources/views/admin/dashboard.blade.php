@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')

				<div class="content">
					<div class="row">
						<div class="col-xl-3 col-sm-6 p-b-15 lbl-card">
							<div class="card card-mini dash-card card-1">
								<div class="card-body">
									<h2 class="mb-1">87</h2>
									<p>Pesakit Harian</p>
									<span class="mdi mdi-account-plus"></span>
								</div>
							</div>
						</div>
						<div class="col-xl-3 col-sm-6 p-b-15 lbl-card">
							<div class="card card-mini dash-card card-2">
								<div class="card-body">
									<h2 class="mb-1">320</h2>
									<p>Jumlah Kunjungan Bulan Ini</p>
									<span class="mdi mdi-calendar-month"></span>
								</div>
							</div>
						</div>
						<div class="col-xl-3 col-sm-6 p-b-15 lbl-card">
							<div class="card card-mini dash-card card-3">
								<div class="card-body">
									<h2 class="mb-1">12</h2>
									<p>Temujanji Hari Ini</p>
									<span class="mdi mdi-calendar-check"></span>
								</div>
							</div>
						</div>
						<div class="col-xl-3 col-sm-6 p-b-15 lbl-card">
							<div class="card card-mini dash-card card-4">
								<div class="card-body">
									<h2 class="mb-1">4</h2>
									<p>Doktor Bertugas</p>
									<span class="mdi mdi-doctor"></span>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-xl-8 col-md-12 p-b-15">
							<div id="user-acquisition" class="card card-default">
								<div class="card-header">
									<h2>Laporan Kunjungan Pesakit</h2>
								</div>
								<div class="card-body">
									<ul class="nav nav-tabs nav-style-border justify-content-between justify-content-lg-start border-bottom"
										role="tablist">
										<li class="nav-item">
											<a class="nav-link active" data-bs-toggle="tab" href="#harian" role="tab"
												aria-selected="true">Harian</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" data-bs-toggle="tab" href="#bulanan" role="tab"
												aria-selected="false">Bulanan </a>
										</li>
										<li class="nav-item">
											<a class="nav-link" data-bs-toggle="tab" href="#tahunan" role="tab"
												aria-selected="false">Tahunan</a>
										</li>
									</ul>
									<div class="tab-content pt-4" id="laporanKunjungan">
										<div class="tab-pane fade show active" id="sumber-medium" role="tabpanel">
											<div class="mb-6" style="max-height:247px">
												<canvas id="acquisition" class="chartjs2"></canvas>
												<div id="acqLegend" class="customLegend mb-2"></div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="col-xl-4 col-md-12 p-b-15">
							<div class="card card-default">
								<div class="card-header justify-content-center">
									<h2>Status Pesakit</h2>
								</div>
								<div class="card-body">
									<canvas id="doChart"></canvas>
								</div>
								<div class="card-footer d-flex flex-wrap bg-white p-0">
									<div class="col-6">
										<div class="p-20">
											<ul class="d-flex flex-column justify-content-between">
												<li class="mb-2"><i class="mdi mdi-checkbox-blank-circle-outline mr-2"
														style="color: #4c84ff"></i>Selesai Periksa</li>
												<li class="mb-2"><i class="mdi mdi-checkbox-blank-circle-outline mr-2"
														style="color: #80e1c1 "></i>Menunggu Pembayaran</li>
											</ul>
										</div>
									</div>
									<div class="col-6 border-left">
										<div class="p-20">
											<ul class="d-flex flex-column justify-content-between">
												<li class="mb-2"><i class="mdi mdi-checkbox-blank-circle-outline mr-2"
														style="color: #8061ef"></i>Menunggu Pemeriksaan</li>
												<li><i class="mdi mdi-checkbox-blank-circle-outline mr-2"
														style="color: #ff7b7b "></i>Lain-lain</li>
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-12 p-b-15">
							<div class="card card-table-border-none card-default recent-orders" id="recent-orders">
								<div class="card-header justify-content-between">
									<h2>Temujanji Terkini</h2>
									<div class="date-range-report">
										<span></span>
									</div>
								</div>
								<div class="card-body pt-0 pb-5">
									<table class="table card-table table-responsive table-responsive-large"
										style="width:100%">
										<thead>
											<tr>
												<th>ID Temujanji</th>
												<th>Nama Pesakit</th>
												<th class="d-none d-lg-table-cell">Doktor</th>
												<th class="d-none d-lg-table-cell">Tarikh</th>
												<th>Status</th>
												<th></th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>JTD001</td>
												<td>
													<a class="text-dark" href="#">Budi Santoso</a>
												</td>
												<td class="d-none d-lg-table-cell">Dr. Ani</td>
												<td class="d-none d-lg-table-cell">20 Mei 2025</td>
												<td>
													<span class="badge badge-success">Selesai</span>
												</td>
												<td class="text-right">
													<div class="dropdown show d-inline-block widget-dropdown">
														<a class="dropdown-toggle icon-burger-mini" href=""
															role="button" id="dropdown-recent-order1"
															data-bs-toggle="dropdown" aria-haspopup="true"
															aria-expanded="false" data-display="static"></a>
														<ul class="dropdown-menu dropdown-menu-right">
															<li class="dropdown-item">
																<a href="#">Lihat</a>
															</li>
															<li class="dropdown-item">
																<a href="#">Hapus</a>
															</li>
														</ul>
													</div>
												</td>
											</tr>
											<tr>
												<td>JTD002</td>
												<td>
													<a class="text-dark" href="#">Siti Aminah</a>
												</td>
												<td class="d-none d-lg-table-cell">Dr. Bima</td>
												<td class="d-none d-lg-table-cell">20 Mei 2025</td>
												<td>
													<span class="badge badge-primary">Dijadwalkan</span>
												</td>
												<td class="text-right">
													<div class="dropdown show d-inline-block widget-dropdown">
														<a class="dropdown-toggle icon-burger-mini" href="#"
															role="button" id="dropdown-recent-order2"
															data-bs-toggle="dropdown" aria-haspopup="true"
															aria-expanded="false" data-display="static"></a>
														<ul class="dropdown-menu dropdown-menu-right">
															<li class="dropdown-item">
																<a href="#">Lihat</a>
															</li>
															<li class="dropdown-item">
																<a href="#">Hapus</a>
															</li>
														</ul>
													</div>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>




@endsection
