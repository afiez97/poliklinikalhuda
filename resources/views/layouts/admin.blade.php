<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="description" content="Ekka - Admin Dashboard eCommerce HTML Template.">

	<title>Ekka - Admin Dashboard eCommerce HTML Template.</title>

	<!-- GOOGLE FONTS -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link
		href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700;800;900&family=Roboto:wght@400;500;700;900&display=swap"
		rel="stylesheet">

	<link href="https://cdn.materialdesignicons.com/4.4.95/css/materialdesignicons.min.css" rel="stylesheet" />
    <!-- PLUGINS CSS STYLE -->
    <link href="{{ asset('assets-admin/plugins/daterangepicker/daterangepicker.css') }}" rel="stylesheet">
    <link href="{{ asset('assets-admin/plugins/simplebar/simplebar.css') }}" rel="stylesheet" />

    <!-- Ekka CSS -->
    <link id="ekka-css" href="{{ asset('assets-admin/css/ekka.css') }}" rel="stylesheet" />

    <!-- Custom Theme CSS -->
    <link href="{{ asset('assets-admin/css/custom-theme.css') }}" rel="stylesheet" />

    <!-- FAVICON -->
    <link href="{{ asset('assets-admin/img/favicon.png') }}" rel="shortcut icon" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

</head>

<body class="ec-header-fixed ec-sidebar-fixed ec-sidebar-dark ec-header-dark" id="body">

	<!--  WRAPPER  -->
	<div class="wrapper">

	<x-admin-navbar />

		<!--  PAGE WRAPPER -->
		<div class="ec-page-wrapper">

			<!-- Header -->
			<header class="ec-main-header" id="header">
				<nav class="navbar navbar-static-top navbar-expand-lg">
					<!-- Sidebar toggle button -->
					<button id="sidebar-toggler" class="sidebar-toggle"></button>
					<!-- search form -->
					<div class="search-form d-lg-inline-block">
						<div class="input-group">
							<input type="text" name="query" id="search-input" class="form-control"
								placeholder="search.." autofocus autocomplete="off" />
							<button type="button" name="search" id="search-btn" class="btn btn-flat">
								<i class="mdi mdi-magnify"></i>
							</button>
						</div>
						<div id="search-results-container">
							<ul id="search-results"></ul>
						</div>
					</div>

					<!-- navbar right -->
					<div class="navbar-right">
						<ul class="nav navbar-nav">
							@auth
							<!-- User Account -->
							<li class="dropdown user-menu">
								<button class="dropdown-toggle nav-link ec-drop" data-bs-toggle="dropdown"
									aria-expanded="false">
									@if(auth()->user()->avatar)
										<img src="{{ auth()->user()->avatar }}" class="user-image" alt="User Image" />
									@else
										<img src="{{ asset('assets-admin/img/user/user.png') }}" class="user-image" alt="User Image" />
									@endif
								</button>
								<ul class="dropdown-menu dropdown-menu-right ec-dropdown-menu">
									<!-- User image -->
									<li class="dropdown-header">
										@if(auth()->user()->avatar)
											<img src="{{ auth()->user()->avatar }}" class="img-circle" alt="User Image" />
										@else
											<img src="{{ asset('assets-admin/img/user/user.png') }}" class="img-circle" alt="User Image" />
										@endif
										<div class="d-inline-block">
											{{ auth()->user()->name }} <small class="pt-1">{{ auth()->user()->email }}</small>
										</div>
									</li>
									<li>
										<a href="user-profile.html">
											<i class="mdi mdi-account"></i> My Profile
										</a>
									</li>
									<li>
										<a href="#">
											<i class="mdi mdi-email"></i> Message
										</a>
									</li>
									<li>
										<a href="#"> <i class="mdi mdi-diamond-stone"></i> Projects </a>
									</li>
									<li class="right-sidebar-in">
										<a href="javascript:0"> <i class="mdi mdi-settings-outline"></i> Setting </a>
									</li>
									<li class="dropdown-footer">
										<form method="POST" action="{{ route('logout') }}" class="d-inline w-100">
											@csrf
											<a href="#" onclick="event.preventDefault(); this.closest('form').submit();" class="text-decoration-none">
												<i class="mdi mdi-logout"></i> {{ __('common.logout') }}
											</a>
										</form>
									</li>
								</ul>
							</li>
							@else
							<!-- Login link for guests -->
							<li class="nav-item">
								<a href="{{ route('admin.login') }}" class="nav-link">
									<i class="mdi mdi-login"></i> Login
								</a>
							</li>
							@endauth
							<li class="dropdown notifications-menu custom-dropdown">
								<button class="dropdown-toggle notify-toggler custom-dropdown-toggler">
									<i class="mdi mdi-bell-outline"></i>
								</button>

								<div class="card card-default dropdown-notify dropdown-menu-right mb-0">
									<div class="card-header card-header-border-bottom px-3">
										<h2>Notifications</h2>
									</div>

									<div class="card-body px-0 py-0">
										<ul class="nav nav-tabs nav-style-border p-0 justify-content-between" id="myTab"
											role="tablist">
											<li class="nav-item mx-3 my-0 py-0">
												<a href="#" class="nav-link active pb-3" id="home2-tab"
													data-bs-toggle="tab" data-bs-target="#home2" role="tab"
													aria-controls="home2" aria-selected="true">All (10)</a>
											</li>

											<li class="nav-item mx-3 my-0 py-0">
												<a href="#" class="nav-link pb-3" id="profile2-tab" data-bs-toggle="tab"
													data-bs-target="#profile2" role="tab" aria-controls="profile2"
													aria-selected="false">Msgs (5)</a>
											</li>

											<li class="nav-item mx-3 my-0 py-0">
												<a href="#" class="nav-link pb-3" id="contact2-tab" data-bs-toggle="tab"
													data-bs-target="#contact2" role="tab" aria-controls="contact2"
													aria-selected="false">Others (5)</a>
											</li>
										</ul>

										<div class="tab-content" id="myNotifications">
											<div class="tab-pane fade show active" id="home2" role="tabpanel">
												<ul class="list-unstyled" data-simplebar style="height: 360px">
													<li>
														<a href="javscript:void(0)"
															class="media media-message media-notification">
															<div class="position-relative mr-3">
																<img class="rounded-circle"
																	src="{{ asset('assets-admin/img/user/u2.jpg') }}" alt="Image">
																<span class="status away"></span>
															</div>
															<div class="media-body d-flex justify-content-between">
																<div class="message-contents">
																	<h4 class="title">Nitin</h4>
																	<p class="last-msg">Lorem ipsum dolor sit, amet
																		consectetur adipisicing elit. Nam itaque
																		doloremque odio, eligendi delectus vitae.</p>

																	<span
																		class="font-size-12 font-weight-medium text-secondary">
																		<i class="mdi mdi-clock-outline"></i> 30 min
																		ago...
																	</span>
																</div>
															</div>
														</a>
													</li>

													<li>
														<a href="javscript:void(0)"
															class="media media-message media-notification media-active">
															<div class="position-relative mr-3">
																<img class="rounded-circle"
																	src="{{ asset('assets-admin/img/user/u1.jpg') }}" alt="Image">
																<span class="status active"></span>
															</div>
															<div class="media-body d-flex justify-content-between">
																<div class="message-contents">
																	<h4 class="title">Lovina</h4>
																	<p class="last-msg">Donec mattis augue a nisl
																		consequat, nec imperdiet ex rutrum. Fusce et
																		vehicula enim. Sed in enim eu odio vehic.</p>

																	<span
																		class="font-size-12 font-weight-medium text-white">
																		<i class="mdi mdi-clock-outline"></i> Just
																		now...
																	</span>
																</div>
															</div>
														</a>
													</li>

													<li>
														<a href="javscript:void(0)"
															class="media media-message media-notification">
															<div class="position-relative mr-3">
																<img class="rounded-circle"
																	src="{{ asset('assets-admin/img/user/u5.jpg') }}" alt="Image">
																<span class="status away"></span>
															</div>
															<div class="media-body d-flex justify-content-between">
																<div class="message-contents">
																	<h4 class="title">Crinali</h4>
																	<p class="last-msg">Lorem ipsum dolor sit, amet
																		consectetur adipisicing elit. Nam itaque
																		doloremque odio, eligendi delectus vitae.</p>

																	<span
																		class="font-size-12 font-weight-medium text-secondary">
																		<i class="mdi mdi-clock-outline"></i> 1 hrs
																		ago...
																	</span>
																</div>
															</div>
														</a>
													</li>

													<li>
														<a href="javscript:void(0)"
															class="media media-message media-notification event-active">

															<div
																class="d-flex rounded-circle align-items-center justify-content-center mr-3 media-icon iconbox-45 bg-info text-white">
																<i class="mdi mdi-calendar-check font-size-20"></i>
															</div>

															<div class="media-body d-flex justify-content-between">
																<div class="message-contents">
																	<h4 class="title">Upcomming event added</h4>
																	<p class="last-msg font-size-14">03/Jan/2020 (1pm -
																		2pm)</p>

																	<span
																		class="font-size-12 font-weight-medium text-secondary">
																		<i class="mdi mdi-clock-outline"></i> 10 min
																		ago...
																	</span>
																</div>
															</div>
														</a>
													</li>

													<li>
														<a href="javscript:void(0)"
															class="media media-message media-notification">

															<div
																class="d-flex rounded-circle align-items-center justify-content-center mr-3 media-icon iconbox-45 bg-warning text-white">
																<i class="mdi mdi-chart-areaspline font-size-20"></i>
															</div>

															<div class="media-body d-flex justify-content-between">
																<div class="message-contents">
																	<h4 class="title">Yearly Sales report</h4>
																	<p class="last-msg font-size-14">Lorem ipsum dolor
																		sit, amet consectetur adipisicing elit. Nam
																		itaque doloremque odio, eligendi delectus vitae.
																	</p>

																	<span
																		class="font-size-12 font-weight-medium text-secondary">
																		<i class="mdi mdi-clock-outline"></i> 1 hrs
																		ago...
																	</span>
																</div>
															</div>
														</a>
													</li>

													<li>
														<a href="javscript:void(0)"
															class="media media-message media-notification">

															<div
																class="d-flex rounded-circle align-items-center justify-content-center mr-3 media-icon iconbox-45 bg-primary text-white">
																<i
																	class="mdi mdi-account-multiple-check font-size-20"></i>
															</div>

															<div class="media-body d-flex justify-content-between">
																<div class="message-contents">
																	<h4 class="title">New request</h4>
																	<p class="last-msg font-size-14">Add Dany Jones as
																		your contact consequat nec imperdiet ex rutrum.
																		Fusce et vehicula enim. Sed in enim.</p>

																	<span
																		class="my-1 btn btn-sm btn-success">Accept</span>
																	<span
																		class="my-1 btn btn-sm btn-secondary">Delete</span>

																	<span
																		class="font-size-12 font-weight-medium text-secondary d-block">
																		<i class="mdi mdi-clock-outline"></i> 5 min
																		ago...
																	</span>
																</div>
															</div>
														</a>
													</li>

													<li>
														<a href="javscript:void(0)"
															class="media media-message media-notification">

															<div
																class="d-flex rounded-circle align-items-center justify-content-center mr-3 media-icon iconbox-45 bg-danger text-white">
																<i class="mdi mdi-server-network-off font-size-20"></i>
															</div>

															<div class="media-body d-flex justify-content-between">
																<div class="message-contents">
																	<h4 class="title">Server overloaded</h4>
																	<p class="last-msg font-size-14">Donec mattis augue
																		a nisl consequat, nec imperdiet ex rutrum. Fusce
																		et vehicula enim. Sed in enim eu odio vehic.</p>

																	<span
																		class="font-size-12 font-weight-medium text-secondary">
																		<i class="mdi mdi-clock-outline"></i> 30 min
																		ago...
																	</span>
																</div>
															</div>
														</a>
													</li>

													<li>
														<a href="javscript:void(0)"
															class="media media-message media-notification">

															<div
																class="d-flex rounded-circle align-items-center justify-content-center mr-3 media-icon iconbox-45 bg-purple text-white">
																<i class="mdi mdi-playlist-check font-size-20"></i>
															</div>

															<div class="media-body d-flex justify-content-between">
																<div class="message-contents">
																	<h4 class="title">Task complete</h4>
																	<p class="last-msg font-size-14">Nam ut nisi erat.
																		Ut quis tortor varius, hendrerit arcu quis,
																		congue nisl. In scelerisque, sem ut ve.</p>

																	<span
																		class="font-size-12 font-weight-medium text-secondary">
																		<i class="mdi mdi-clock-outline"></i> 2 hrs
																		ago...
																	</span>
																</div>
															</div>
														</a>
													</li>

												</ul>
											</div>

											<div class="tab-pane fade" id="profile2" role="tabpanel">
												<ul class="list-unstyled" data-simplebar style="height: 360px">
													<li>
														<a href="javscript:void(0)"
															class="media media-message media-notification">
															<div class="position-relative mr-3">
																<img class="rounded-circle"
																	src="{{ asset('assets-admin/img/user/u6.jpg') }}" alt="Image">
																<span class="status away"></span>
															</div>
															<div class="media-body d-flex justify-content-between">
																<div class="message-contents">
																	<h4 class="title">Hardiko</h4>
																	<p class="last-msg">Donec mattis augue a nisl
																		consequat, nec imperdiet ex rutrum. Fusce et
																		vehicula enim. Sed in enim eu odio vehic.</p>

																	<span
																		class="font-size-12 font-weight-medium text-secondary">
																		<i class="mdi mdi-clock-outline"></i> 1 hrs
																		ago...
																	</span>
																</div>
															</div>
														</a>
													</li>

													<li>
														<a href="javscript:void(0)"
															class="media media-message media-notification">
															<div class="position-relative mr-3">
																<img class="rounded-circle"
																	src="{{ asset('assets-admin/img/user/u7.jpg') }}" alt="Image">
																<span class="status away"></span>
															</div>
															<div class="media-body d-flex justify-content-between">
																<div class="message-contents">
																	<h4 class="title">Browin</h4>
																	<p class="last-msg">Nam ut nisi erat. Ut quis tortor
																		varius, hendrerit arcu quis, congue nisl. In
																		scelerisque, sem ut ve.</p>

																	<span
																		class="font-size-12 font-weight-medium text-secondary">
																		<i class="mdi mdi-clock-outline"></i> 1 hrs
																		ago...
																	</span>
																</div>
															</div>
														</a>
													</li>

													<li>
														<a href="javscript:void(0)"
															class="media media-message media-notification media-active">
															<div class="position-relative mr-3">
																<img class="rounded-circle"
																	src="{{ asset('assets-admin/img/user/u1.jpg') }}" alt="Image">
																<span class="status active"></span>
															</div>
															<div class="media-body d-flex justify-content-between">
																<div class="message-contents">
																	<h4 class="title">jenelia</h4>
																	<p class="last-msg">Donec mattis augue a nisl
																		consequat, nec imperdiet ex rutrum. Fusce et
																		vehicula enim. Sed in enim eu odio vehic.</p>

																	<span
																		class="font-size-12 font-weight-medium text-white">
																		<i class="mdi mdi-clock-outline"></i> Just
																		now...
																	</span>
																</div>
															</div>
														</a>
													</li>

													<li>
														<a href="javscript:void(0)"
															class="media media-message media-notification">
															<div class="position-relative mr-3">
																<img class="rounded-circle"
																	src="{{ asset('assets-admin/img/user/u2.jpg') }}" alt="Image">
																<span class="status away"></span>
															</div>
															<div class="media-body d-flex justify-content-between">
																<div class="message-contents">
																	<h4 class="title">Bhavlio</h4>
																	<p class="last-msg">Lorem ipsum dolor sit, amet
																		consectetur adipisicing elit. Nam itaque
																		doloremque odio, eligendi delectus vitae.</p>

																	<span
																		class="font-size-12 font-weight-medium text-secondary">
																		<i class="mdi mdi-clock-outline"></i> 1 hrs
																		ago...
																	</span>
																</div>
															</div>
														</a>
													</li>

													<li>
														<a href="javscript:void(0)"
															class="media media-message media-notification">
															<div class="position-relative mr-3">
																<img class="rounded-circle"
																	src="{{ asset('assets-admin/img/user/u5.jpg') }}" alt="Image">
																<span class="status away"></span>
															</div>
															<div class="media-body d-flex justify-content-between">
																<div class="message-contents">
																	<h4 class="title">Browini</h4>
																	<p class="last-msg">Lorem ipsum dolor sit, amet
																		consectetur adipisicing elit. Nam itaque
																		doloremque odio, eligendi delectus vitae.</p>

																	<span
																		class="font-size-12 font-weight-medium text-secondary">
																		<i class="mdi mdi-clock-outline"></i> 1 hrs
																		ago...
																	</span>
																</div>
															</div>
														</a>
													</li>

												</ul>
											</div>

											<div class="tab-pane fade" id="contact2" role="tabpanel">
												<ul class="list-unstyled" data-simplebar style="height: 360px">
													<li>
														<a href="javscript:void(0)"
															class="media media-message media-notification event-active">

															<div
																class="d-flex rounded-circle align-items-center justify-content-center mr-3 media-icon iconbox-45 bg-info text-white">
																<i class="mdi mdi-calendar-check font-size-20"></i>
															</div>

															<div class="media-body d-flex justify-content-between">
																<div class="message-contents">
																	<h4 class="title">Upcomming event added</h4>
																	<p class="last-msg font-size-14">03/Jan/2020 (1pm -
																		2pm)</p>

																	<span
																		class="font-size-12 font-weight-medium text-secondary">
																		<i class="mdi mdi-clock-outline"></i> 10 min
																		ago...
																	</span>
																</div>
															</div>
														</a>
													</li>

													<li>
														<a href="javscript:void(0)"
															class="media media-message media-notification">

															<div
																class="d-flex rounded-circle align-items-center justify-content-center mr-3 media-icon iconbox-45 bg-warning text-white">
																<i class="mdi mdi-chart-areaspline font-size-20"></i>
															</div>

															<div class="media-body d-flex justify-content-between">
																<div class="message-contents">
																	<h4 class="title">New Sales report</h4>
																	<p class="last-msg font-size-14">Lorem ipsum dolor
																		sit, amet consectetur adipisicing elit. Nam
																		itaque doloremque odio, eligendi delectus vitae.
																	</p>

																	<span
																		class="font-size-12 font-weight-medium text-secondary">
																		<i class="mdi mdi-clock-outline"></i> 1 hrs
																		ago...
																	</span>
																</div>
															</div>
														</a>
													</li>

													<li>
														<a href="javscript:void(0)"
															class="media media-message media-notification">

															<div
																class="d-flex rounded-circle align-items-center justify-content-center mr-3 media-icon iconbox-45 bg-primary text-white">
																<i
																	class="mdi mdi-account-multiple-check font-size-20"></i>
															</div>

															<div class="media-body d-flex justify-content-between">
																<div class="message-contents">
																	<h4 class="title">New Request</h4>
																	<p class="last-msg font-size-14">Add Dany Jones as
																		your contact consequat nec imperdiet ex rutrum.
																		Fusce et vehicula enim. Sed in enim.</p>

																	<span
																		class="my-1 btn btn-sm btn-success">Accept</span>
																	<span
																		class="my-1 btn btn-sm btn-secondary">Delete</span>

																	<span
																		class="font-size-12 font-weight-medium text-secondary d-block">
																		<i class="mdi mdi-clock-outline"></i> 5 min
																		ago...
																	</span>
																</div>
															</div>
														</a>
													</li>

													<li>
														<a href="javscript:void(0)"
															class="media media-message media-notification">

															<div
																class="d-flex rounded-circle align-items-center justify-content-center mr-3 media-icon iconbox-45 bg-danger text-white">
																<i class="mdi mdi-server-network-off font-size-20"></i>
															</div>

															<div class="media-body d-flex justify-content-between">
																<div class="message-contents">
																	<h4 class="title">Server overloaded</h4>
																	<p class="last-msg font-size-14">Donec mattis augue
																		a nisl consequat, nec imperdiet ex rutrum. Fusce
																		et vehicula enim. Sed in enim eu odio vehic.</p>

																	<span
																		class="font-size-12 font-weight-medium text-secondary">
																		<i class="mdi mdi-clock-outline"></i> 30 min
																		ago...
																	</span>
																</div>
															</div>
														</a>
													</li>

													<li>
														<a href="javscript:void(0)"
															class="media media-message media-notification">

															<div
																class="d-flex rounded-circle align-items-center justify-content-center mr-3 media-icon iconbox-45 bg-purple text-white">
																<i class="mdi mdi-playlist-check font-size-20"></i>
															</div>

															<div class="media-body d-flex justify-content-between">
																<div class="message-contents">
																	<h4 class="title">New Task complete</h4>
																	<p class="last-msg font-size-14">Nam ut nisi erat.
																		Ut quis tortor varius, hendrerit arcu quis,
																		congue nisl. In scelerisque, sem ut ve.</p>

																	<span
																		class="font-size-12 font-weight-medium text-secondary">
																		<i class="mdi mdi-clock-outline"></i> 2 hrs
																		ago...
																	</span>
																</div>
															</div>
														</a>
													</li>
												</ul>
											</div>
										</div>
									</div>
								</div>

								<ul class="dropdown-menu dropdown-menu-right d-none">
									<li class="dropdown-header">You have 5 notifications</li>
									<li>
										<a href="#">
											<i class="mdi mdi-account-plus"></i> New user registered
											<span class=" font-size-12 d-inline-block float-right"><i
													class="mdi mdi-clock-outline"></i> 10 AM</span>
										</a>
									</li>
									<li>
										<a href="#">
											<i class="mdi mdi-account-remove"></i> User deleted
											<span class=" font-size-12 d-inline-block float-right"><i
													class="mdi mdi-clock-outline"></i> 07 AM</span>
										</a>
									</li>
									<li>
										<a href="#">
											<i class="mdi mdi-chart-areaspline"></i> Sales report is ready
											<span class=" font-size-12 d-inline-block float-right"><i
													class="mdi mdi-clock-outline"></i> 12 PM</span>
										</a>
									</li>
									<li>
										<a href="#">
											<i class="mdi mdi-account-supervisor"></i> New client
											<span class=" font-size-12 d-inline-block float-right"><i
													class="mdi mdi-clock-outline"></i> 10 AM</span>
										</a>
									</li>
									<li>
										<a href="#">
											<i class="mdi mdi-server-network-off"></i> Server overloaded
											<span class=" font-size-12 d-inline-block float-right"><i
													class="mdi mdi-clock-outline"></i> 05 AM</span>
										</a>
									</li>
									<li class="dropdown-footer">
										<a class="text-center" href="#"> View All </a>
									</li>
								</ul>
							</li>
							<li class="right-sidebar-in right-sidebar-2-menu">
								<i class="mdi mdi-settings-outline mdi-spin"></i>
							</li>
						</ul>
					</div>
				</nav>
			</header>

			<div class="ec-content-wrapper">
				<div class="content">
					<div class="row">
						<div class="col-xl-3 col-sm-6 p-b-15 lbl-card">
							<div class="card card-mini dash-card card-1">
								<div class="card-body">
									<h2 class="mb-1">85</h2>
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

					@yield('content')
				</div>
			</div>
			<!-- Footer -->
			<footer class="footer mt-auto">
				<div class="copyright bg-white">
					<p>
						Copyright &copy; <span id="ec-year"></span><a class="text-primary"
							href="https://themeforest.net/user/ashishmaraviya" target="_blank"> Ekka Admin
							Dashboard</a>. All Rights Reserved.
					</p>
				</div>
			</footer>

		</div> <!-- End Page Wrapper -->
	</div> <!-- End Wrapper -->
    <!-- Common Javascript -->
    <script src="{{ asset('assets-admin/plugins/jquery/jquery-3.5.1.min.js') }}"></script>
    <script src="{{ asset('assets-admin/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets-admin/plugins/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets-admin/plugins/jquery-zoom/jquery.zoom.min.js') }}"></script>
    <script src="{{ asset('assets-admin/plugins/slick/slick.min.js') }}"></script>

    <!-- Chart -->
    <script src="{{ asset('assets-admin/plugins/charts/Chart.min.js') }}"></script>
    <script src="{{ asset('assets-admin/js/chart.js') }}"></script>

    <!-- Google map chart -->
    <script src="{{ asset('assets-admin/plugins/charts/google-map-loader.js') }}"></script>
    <script src="{{ asset('assets-admin/plugins/charts/google-map.js') }}"></script>

    <!-- Date Range Picker -->
    <script src="{{ asset('assets-admin/plugins/daterangepicker/moment.min.js') }}"></script>
    <script src="{{ asset('assets-admin/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('assets-admin/js/date-range.js') }}"></script>

    <!-- Option Switcher -->
    <script src="{{ asset('assets-admin/plugins/options-sidebar/optionswitcher.js') }}"></script>

    <!-- Dashboard Charts Init -->
    <script>
        $(document).ready(function() {
            // Patient Visit Chart
            var ctx = document.getElementById('acquisition').getContext('2d');
            var acquisition = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Ahd'],
                    datasets: [{
                        label: 'Kunjungan Pesakit',
                        data: [12, 19, 15, 17, 23, 18, 20],
                        backgroundColor: 'rgba(76, 132, 255, 0.1)',
                        borderColor: '#4c84ff',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            // Patient Status Doughnut Chart
            var doCtx = document.getElementById('doChart').getContext('2d');
            var doChart = new Chart(doCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Selesai Periksa', 'Menunggu Pembayaran', 'Menunggu Pemeriksaan', 'Lain-lain'],
                    datasets: [{
                        data: [45, 25, 20, 10],
                        backgroundColor: ['#4c84ff', '#80e1c1', '#8061ef', '#ff7b7b'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '80%',
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        });
    </script>

    <!-- Ekka Custom -->
    <script src="{{ asset('assets-admin/js/ekka.js') }}"></script>
</body>

</html>
