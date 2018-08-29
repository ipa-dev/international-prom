<div class="metabox-holder">
	<div class="meta-box-sortables ui-sortable">
		<div id="esb_cie_product_product_tag" class="postbox">

			<!-- settings box title -->
			<h3 class="hndle">
				<span style="vertical-align: top;">Twilio Text Price</span>
			</h3>

			<div class="inside">
				<!-- Export into file Start -->
				<table class="form-table esb-cie-form-table">
					<tbody><tr>
						<td colspan="2" valign="top" scope="row">
							<strong>Export into file</strong>
						</td>
					</tr>

					</tbody>
				</table>

				<p>
					<a class="button button-primary button-large" href="<?php echo admin_url( 'admin-post.php?action=color_export_csv' ); ?>">Export</a>
				</p>
				<!-- Export into file End -->
				<!-- Import from file Start -->
				<table class="form-table esb-cie-form-table">
					<tbody><tr>
						<td colspan="2" valign="top" scope="row">
							<strong>Import from file</strong>
						</td>
					</tr>

					</tbody>
				</table>

				<form method="POST" action="" enctype="multipart/form-data">
					<p>
						<input type="file" name="upload_csv" accept=".csv">
						<input type="submit" name="color_import_submit" class="button-secondary" value="Import From CSV">
					</p>
				</form>
				<!-- Import from file End -->

			</div><!-- .inside -->

		</div><!-- #settings -->
	</div><!-- .meta-box-sortables ui-sortable -->
</div>