  <footer class="main-footer">
    
    <strong>Copyright &copy; 2017 .</strong> All rights
    reserved.
  </footer>

  <aside class="control-sidebar control-sidebar-dark">
    <div class="tab-content">
      <div class="tab-pane active">
        <ul class="control-sidebar-menu">
		
			<?php 
			$all_messages = $this->Mdl_common->all_message('Writer');
			$all_messages_me = $this->Mdl_common->all_message_me('Writer');
			$user = array();
			foreach($all_messages_me as $all_message_me)
			{
				$user[] =  array(
					'assignment_id' => $all_message_me['assignment_id'],
					'to_id' => $all_message_me['from_id'],
					'to_role' => $all_message_me['from_role'],
				);
			}
			foreach($all_messages as $all_message)
			{
				$user[] =  array(
					'assignment_id' => $all_message['assignment_id'],
					'to_id' => $all_message['to_id'],
					'to_role' => $all_message['to_role'],
				);
			}
			$user = array_map("unserialize", array_unique(array_map("serialize", $user)));
			foreach($user as $data)
			{
				$to_id = $this->Mdl_common->user_get($data['to_id']);
				if($to_id[0]['status'] == 1)
				{
					if($data['to_role'] == "Help Desk")
					{
						$status = '5';
					}else if($data['to_role'] == "Admin"){
						$status = '1';
					}else if($data['to_role'] == "Manager"){
						$status = '2';
					}else if($data['to_role'] == "Writer"){
						$status = '3';
					}else if($data['to_role'] == "Proof Reader"){
						$status = '4';
					}
					$proof_reader_url = "javascript:register_popup('".$to_id[0]['id'].$status.$data['assignment_id']."', '".ucfirst($to_id[0]['name'])."','".$to_id[0]['id']."','".$data['to_role']."','".$data['assignment_id']."');";
					echo '<li>
						<a href="'.$proof_reader_url.'">
							<i class="menu-icon fa fa-envelope-o bg-light-blue"></i>
							<div class="menu-info">
								<h4 class="control-sidebar-subheading">'.ucfirst($to_id[0]['name']).' <small> ('.$data['to_role'].')</small> </h4><p>Assignment Id '.$data['assignment_id'].'</p></div>
						</a>
					</li>';
				}
			}
			?>
        </ul>
      </div>
    </div>
  </aside>
  <div class="control-sidebar-bg"></div>
</div>
<style>
	.popup-box
	{
		display: none;
		position: fixed;
		bottom: -20px;
		right: 220px;
		width: 300px;
	}
</style>
 <?php if($left_sidebar != 'Writer Message'){?>
<script>
	function setMarkerss(ids) {
		setTimeout(function () {
			var count = document.getElementById("count"+ids).value;
			var to_id = document.getElementById("to_id"+ids).value;
			var to_role = document.getElementById("to_role"+ids).value;
			var assignment_id = document.getElementById("assignment_id"+ids).value;
			var from_role = "Writer";
			var scrol = document.getElementById("scrol"+ids).value;
			$.ajax({
				url: '<?php echo base_url(); ?>index.php/Message/message_get/',
				type: "POST",
				data : ({ to_id: to_id,to_role: to_role,from_role: from_role,assignment_id: assignment_id,count: count }),
				dataType: "json",
				success: function (data){
					if(data.status == 1)
					{
						$(".message_boxs"+ids).append(data.datas);
						$(".message_boxs"+ids).stop().animate({ 
							scrollTop: $(".message_boxs"+ids)[0].scrollHeight
						}, 1000);
						document.getElementById("count"+ids).value = data.count;
					}else if(data.status == 0)
					{
						document.getElementById("count"+ids).value = data.count;
					}
					if(scrol == 1){
						$(".message_boxs"+ids).stop().animate({ 
							scrollTop: $(".message_boxs"+ids)[0].scrollHeight
						}, 1000);
						document.getElementById("scrol"+ids).value = 2;
					}
					setMarkerss(ids);
				}
			});
			
		}, 500);
	}
</script>
<script>
function message_remove(id)
{
	var to_id = document.getElementById("to_id"+id).value;
	var to_role = document.getElementById("to_role"+id).value;
	var assignment_id = document.getElementById("assignment_id"+id).value;
	var from_role = "Writer";
	$.ajax({
		url: '<?php echo base_url(); ?>index.php/Message/message_remove/',
		type: "POST",
		data : ({ to_id: to_id,to_role: to_role,from_role: from_role,assignment_id: assignment_id }),
		success: function (data){
			$(".message_boxs"+id).html('');
			document.getElementById("count"+id).value = 0;
		}
	});
}
function message_adds(id)
{
	var to_id = document.getElementById("to_id"+id).value;
	var to_role = document.getElementById("to_role"+id).value;
	var assignment_id = document.getElementById("assignment_id"+id).value;
	var from_role = "Writer";
	var message = document.getElementById("message"+id).value;
	
	if(message != "")
	{
		document.getElementById("message"+id).value = "";
		$.ajax({
			url: '<?php echo base_url(); ?>index.php/Message/message_add/',
			type: "POST",
			data : ({ to_id: to_id,to_role: to_role,from_role: from_role,assignment_id: assignment_id,message: message }),
			success: function (data) {
				document.getElementById("message"+id).value = "";
			}
		});
	}
}
function box_minus(id)
{
	if($(".box_body_"+id).css('display') == 'block')
	{
		$(".box_body_"+id).hide();
		$(".box_footer_"+id).hide();
		$(".box_minu"+id).html('<i class="fa fa-plus"></i>');
	}else if($(".box_body_"+id).css('display') == 'none'){
		$(".box_body_"+id).show();
		$(".box_footer_"+id).show();
		$(".box_minu"+id).html('<i class="fa fa-minus"></i>');
	}
}	
            Array.remove = function(array, from, to) {
                var rest = array.slice((to || from) + 1 || array.length);
                array.length = from < 0 ? array.length + from : from;
                return array.push.apply(array, rest);
            };
            var total_popups = 0;
            var popups = [];
            function close_popup(id)
            {
				close_sessone(id);
                for(var iii = 0; iii < popups.length; iii++)
                {
                    if(id == popups[iii])
                    {
                        Array.remove(popups, iii);                       
                        document.getElementById(id).style.display = "none";                       
                        calculate_popups();                       
                        return;
                    }
                }  
            }
            function display_popups()
            {
                var right = 244;               
                var iii = 0;
                for(iii; iii < total_popups; iii++)
                {
                    if(popups[iii] != undefined)
                    {
                        var element = document.getElementById(popups[iii]);
                        element.style.right = right + "px";
                        right = right + 311;
                        element.style.display = "block";
                    }
                }               
                for(var jjj = iii; jjj < popups.length; jjj++)
                {
                    var element = document.getElementById(popups[jjj]);
                    element.style.display = "none";
                }
            }
            function register_popup(id,name,to_id,to_role,assignment_id)
            {
				for(var iii = 0; iii < popups.length; iii++)
				{  
					if(id == popups[iii])
					{
						Array.remove(popups, iii);
						popups.unshift(id);
						calculate_popups();
						return;
					}
				}
				var element = '<div class="popup-box box box-primary direct-chat direct-chat-primary" id="'+ id +'" style="border-style: solid;border-color: rgb(60, 141, 188);" ><div class="box-header with-border"  style="border-bottom-color: rgb(60, 141, 188);" ><h3 class="box-title">'+ name +' <small style="left: 65px">('+ to_role +')   (<a href="<?php echo base_url(); ?>index.php/Writer_dashboard/assignment_view/'+assignment_id+'">ID '+assignment_id+'</a>)</small></h3><div class="box-tools pull-right"> <button class="btn btn-box-tool" title="Cline Chate" onclick="message_remove('+ id +')" ><i class="fa fa-trash"></i></button>      <button type="button" class="btn btn-box-tool box_minu'+ id +'" onclick="box_minus('+ id +')" ><i class="fa fa-minus" ></i></button>   <a href="javascript:close_popup(\''+ id +'\');" class="btn btn-box-tool" ><i class="fa fa-times"></i></a></div></div><div class="box-body box_body_'+ id +'" style=""><div class="direct-chat-messages message_boxs'+ id +'"></div></div><div class="box-footer box_footer_'+ id +'" style=""><form action="javascript:message_adds('+ id +');"><div class="input-group"><input type="hidden" name="to_id'+ id +'" id="to_id'+ id +'" value="'+ to_id +'"><input type="hidden" name="to_role'+ id +'" id="to_role'+ id +'" value="'+ to_role +'"><input type="hidden" name="assignment_id'+ id +'" id="assignment_id'+ id +'" value="'+ assignment_id +'"><input name="message'+ id +'" id="message'+ id +'" placeholder="Type Message ..." class="form-control" type="text"><span class="input-group-btn"><button type="submit" onclick="message_adds('+ id +')" class="btn btn-primary btn-flat">Send</button></span></div></form></div></div><input type="hidden" id="count'+ id +'" name="count'+ id +'" value="" ><input type="hidden" id="scrol'+ id +'" name="scrol'+ id +'" value="1" >';
				document.getElementsByTagName("footer")[0].innerHTML = document.getElementsByTagName("footer")[0].innerHTML + element;    
				setMarkerss(id);
                popups.unshift(id);                       
                calculate_popups();
				add_sessone(id,name,to_id,to_role,assignment_id);			
            }
			function add_sessone(id,name,to_id,to_role,assignment_id)
			{
				var box = $(".box_body_"+id).css('display');
				var from_role = "writer";
				$.ajax({
					url: '<?php echo base_url(); ?>index.php/Message/add_sessone/',
					type: "POST",
					data : ({ id: id,name: name,to_id: to_id,to_role: to_role,assignment_id: assignment_id,box: box,from_role: from_role }),
					success: function (data) {
					}
				});
			}
			function close_sessone(id)
			{
				var from_role = "writer";
				$.ajax({
					url: '<?php echo base_url(); ?>index.php/Message/close_sessone/',
					type: "POST",
					data : ({ id: id,from_role: from_role }),
					success: function (data) {
					}
				});
			}
			clos_sessone();
			function clos_sessone()
			{
				var from_role = "writer";
				$.ajax({
					url: '<?php echo base_url(); ?>index.php/Message/clos_sessone/',
					type: "POST",
					data : ({ from_role: from_role }),
					dataType: "json",
					success: function (data) {
						var json = $.parseJSON(JSON.stringify(data));
						for (var i = 0, length = json.length; i < length; i++) {
							register_popup(json[i].id,json[i].name,json[i].to_id,json[i].to_role,json[i].assignment_id);
							box_minus(json[i].id);
						}
					}
				});
			}
            function calculate_popups()
            {
                var width = window.innerWidth;
                if(width < 540)
                {
                    total_popups = 0;
                }
                else
                {
                    width = width - 200;
                    total_popups = parseInt(width/320);
                }
               
                display_popups();
               
            }
            window.addEventListener("resize", calculate_popups);
            window.addEventListener("load", calculate_popups);
  $.widget.bridge('uibutton', $.ui.button);
</script>
 <?php } ?>
<script src="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/select2/dist/js/select2.full.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/input-mask/jquery.inputmask.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/input-mask/jquery.inputmask.extensions.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/moment/min/moment.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/raphael/raphael.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/morris.js/morris.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/jquery-knob/dist/jquery.knob.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/moment/min/moment.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/timepicker/bootstrap-timepicker.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/iCheck/icheck.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/fastclick/lib/fastclick.js"></script>
<script src="<?php echo base_url(); ?>assets/dist/js/adminlte.min.js"></script>
<script src="<?php echo base_url(); ?>assets/dist/js/pages/dashboard.js"></script>
<script src="<?php echo base_url(); ?>assets/dist/js/demo.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/ckeditor/ckeditor.js"></script>


<script>
  $(function () {
	$('.datatable_asc').DataTable();
	$('.datatable').DataTable( {
        "order": [[ 0, "desc" ]]
    });
	$('.actions_datatable_2').DataTable( {
		"order": [[ 2, "desc" ]],
        scrollX:        true,
        paging:         true,
    });
	//Initialize Select2 Elements
	$('.actions_datatable').DataTable( {
        "order": [[ 3, "desc" ]],
        scrollX:        true,
        paging:         true,
    });
    $('.select2').select2()

    //Datemask dd/mm/yyyy
    $('.datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
    //Datemask2 mm/dd/yyyy
    $('.datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
    //Money Euro
    $('[data-mask]').inputmask()

    //Date range picker
    $('.daterangepicker').daterangepicker()
    //Date range picker with time picker
    $('.daterangepickertime').daterangepicker({ timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A' })
    //Date range as a button
    $('#daterange-btn').daterangepicker(
      {
        ranges   : {
          'Today'       : [moment(), moment()],
          'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month'  : [moment().startOf('month'), moment().endOf('month')],
          'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(29, 'days'),
        endDate  : moment()
      },
      function (start, end) {
        $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
      }
    )

    //Date picker
    $('.datepicker').datepicker({
      autoclose: true
    })

    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass   : 'iradio_minimal-blue'
    })
    //Red color scheme for iCheck
    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
      checkboxClass: 'icheckbox_minimal-red',
      radioClass   : 'iradio_minimal-red'
    })
    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass   : 'iradio_flat-green'
    })

    //Colorpicker
    $('.colorpicker1').colorpicker()
    //color picker with addon
    $('.colorpicker2').colorpicker()

    //Timepicker
    $('.timepicker').timepicker({
      showInputs: false
    })
	
	
	$('.ckeditor').wysihtml5()
	
	
	CKEDITOR.replace('description')
	
  })
</script>
</body>
</html>