<?php /* Template Name: Marketing Manager */ ?>
<?php if ( is_user_logged_in() ) { ?>
	<?php get_header(); ?>
	<?php global $user_ID; ?>
	<?php $user_info = get_userdata( $user_ID ); ?>
	<?php $role = get_user_role( $user_ID ); ?>
	<?php if ( $role == 'retailer' ) { ?>
		<div style="display: none;" id="Loader"></div>
		<input type="hidden" name="site_url" value="<?php bloginfo( 'url' ); ?>" />
		<?php
		$user_timezone = get_user_meta( $user_ID, 'user_timezone', true );
		if ( empty( $user_timezone ) ) {
			$tz            = get_option( 'timezone_string' );
			$user_timezone = $tz;
		}
		$schedule_date = new DateTime( date( 'Y-m-d' ), new DateTimeZone( 'UTC' ) );
		$schedule_date->setTimeZone( new DateTimeZone( $user_timezone ) );
		$date_now = $schedule_date->format( 'Y-m-d' );
		?>
		<input type="hidden" name="date_now" value="<?php echo $date_now; ?>" />
		<input type="hidden" name="timezone" value="<?php echo $user_timezone; ?>" />
		<input type="hidden" class="addCalenderDataStart" name="addCalenderDataStart" value=""/>
		<div id="content" class="cal_wrap" ng-app="myApp" ng-controller="myNgController">
		<div class="talk-bubbl-wrap">
			<div class="maincontent">
				<div class="section group">
					<div class="col span_3_of_12">
						<ul class="cal_nav">
							<li class="active"><a href="javascript:void(0);" ng-click="changeView('month')"><i class="fa fa-th" aria-hidden="true"></i> Month</a></li>
							<li><a href="javascript:void(0);" ng-click="changeView('basicWeek')"><i class="fa fa-th-list" aria-hidden="true"></i> Week</a></li>
							<li><a href="javascript:void(0);" ng-click="changeView('agendaWeek')"><i class="fa fa-th-large" aria-hidden="true"></i> Weeklist</a></li>
							<li><a href="javascript:void(0);" ng-click="changeView('basicDay')"><i class="fa fa-th-list" aria-hidden="true"></i> Day</a></li>
							<li><a href="javascript:void(0);" ng-click="changeView('agendaDay')"><i class="fa fa-th-large" aria-hidden="true"></i> Daylist</a></li>
						</ul>
						<h3>My Calendars</h3>
						<ul class="cal_nav external-events">
							<li><a href="javascript:void(0);" data-type="email" ng-click="renderCalenderData('email');"><i class="fa fa-envelope" aria-hidden="true"></i> Email</a></li>
							<li><a href="javascript:void(0);" data-type="facebook" ng-click="renderCalenderData('facebook');"><i class="fa fa-facebook" aria-hidden="true"></i> Facebook</a></li>
							<li><a href="javascript:void(0);" data-type="twitter" ng-click="renderCalenderData('twitter');"><i class="fa fa-twitter" aria-hidden="true"></i> Twitter</a></li>
							<li><a href="javascript:void(0);" data-type="pinterest" ng-click="renderCalenderData('pinterest');"><i class="fa fa-pinterest" aria-hidden="true"></i> Pinterest</a></li>
							<!--<li><a href="javascript:void(0);" data-type="instagram" ng-click="renderCalenderData('instagram');"><i class="fa fa-instagram" aria-hidden="true"></i> Instagram</a></li>-->
							<li><a href="javascript:void(0);" data-type="sms" ng-click="renderCalenderData('sms');"><i class="fa fa-comment" aria-hidden="true"></i> Text Message</a></li>
							<li><a href="javascript:void(0);" data-type="vip" ng-click="renderCalenderData('vip');"><i class="fa fa-eercast" aria-hidden="true"></i> VIP Event</a></li>
							<li><a href="javascript:void(0);" data-type="content_ideas" ng-click="renderCalenderData('content_ideas');"><i class="fa fa-lightbulb-o" aria-hidden="true"></i> Content Ideas</a></li>
						</ul>
					</div>
					<div class="col span_9_of_12">
						<div class="section group">
							<div class="col span_12_of_12">
								<div id="calendar" ui-calendar="uiConfig.calendar" ng-model="eventSources" calendar="myCalendar"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="talk-bubble tri-right left-top" style="display: none;">
				<div class="talktext">
					<p>
						<a href="javascript:void(0);" data-type="email" ng-click="addCalenderData('email');"><i class="fa fa-envelope" aria-hidden="true"></i> Email</a>
						</br>
						<a href="javascript:void(0);" data-type="facebook" ng-click="addCalenderData('facebook');"><i class="fa fa-facebook" aria-hidden="true"></i> Facebook</a>
						</br>
						<a href="javascript:void(0);" data-type="twitter" ng-click="addCalenderData('twitter');"><i class="fa fa-twitter" aria-hidden="true"></i> Twitter</a>
						</br>
						<a href="javascript:void(0);" data-type="pinterest" ng-click="addCalenderData('pinterest');"><i class="fa fa-pinterest" aria-hidden="true"></i> Pinterest</a>
						</br>
						<a href="javascript:void(0);" data-type="sms" ng-click="addCalenderData('sms');"><i class="fa fa-comment" aria-hidden="true"></i> Text Message</a>
						</br>
						<a href="javascript:void(0);" data-type="vip" ng-click="addCalenderData('vip');"><i class="fa fa-eercast" aria-hidden="true"></i> VIP Event</a>
						<br/>
						<a href="javascript:void(0);" data-type="content_ideas" ng-click="addCalenderData('content_ideas');"><i class="fa fa-lightbulb-o" aria-hidden="true"></i> Content Ideas</a>
					</p>
				</div>
			</div>
		</div>
		</div>
		<?php
		$settings1 = array(
			'wpautop'          => true,
			'media_buttons'    => true,
			'textarea_name'    => 'emailbody',
			'textarea_rows'    => 40,
			'tabindex'         => '',
			'editor_css'       => '',
			'editor_class'     => 'msgClass',
			'teeny'            => false,
			'dfw'              => false,
			'tinymce'          => false,
			'quicktags'        => true,
			'drag_drop_upload' => true,
		);

		wp_editor( '', 'emailbody', $settings1 );} else {
	?>
		<div id="content">
			<div class="maincontent">
				<div class="section group">
					<div class="col span_12_of_12">
						<div class="container">
							<p>You are not a retailer, please sign in with a retailer account details.</p>
						</div>
					</div>
				</div>
			</div>
		</div>
			<?php } ?>
	<?php get_footer(); ?>
<?php } else {
	header( 'Location: ' . get_bloginfo( 'home' ) . '/sign-in/?role=retailer&redirect_id=' . get_the_ID() );
} ?>
