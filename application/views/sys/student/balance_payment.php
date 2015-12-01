<!DOCTYPE html>
<html>
<head>
    <?php $this->load->view('inc/head') ?>
    <!-- iCheck -->
    <link rel="stylesheet" href="<?=base_url()?>assets/lib/iCheck/skins/minimal/green.css">
</head>
<body>
<!-- top bar -->
<?php $this->load->view('inc/header') ?>
<!-- main content -->
<div id="main_wrapper">
    <div class="page_content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <fieldset>
                                <legend><span>Student Class Enrollment</span>

                                    <div class="site_nav">
                                        <a href="<?= base_url() ?>">Dashboard</a>
                                        &raquo;<a href="<?= base_url() ?>sys/student"> Student </a>
                                        &raquo; Class Enrollment
                                    </div>
                                </legend>

                            </fieldset>

                            <?php
                            $error= isset($error)? $error : $this->session->flashdata('error');
                            $valid= $this->session->flashdata('valid');

                            if(isset($valid)) $error = $valid;

                            if(isset($error)){
                                ?>
                                <div class="alert <?=isset($valid)?  'alert-success' : 'alert-danger'?> alert-dismissable fade in ">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <?=$error?>
                                </div>
                                <?php
                            }

                            ?>

                            <?php
                            if($this->input->get('tran_id')){
                                ?> <div class="row" >
                                    <div class="col-lg-12 text-right " >
                                        <a href="<?= base_url() ?>sys/student/invoice?tran_id=<?=$this->input->get('tran_id')?>" target="_blank" class="btn btn-default"><i class="fa fa-print fa-lg"></i> Print Previous Invoice  </a>
                                    </div>
                                </div> <?php
                            }
                            ?>

                            <form data-parsley-validate method="post">



                                <div class="row">

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="reg_input">Student</label>
                                            <input  type="text" id="Student_tag" class="form-control model-link " data-url="<?= base_url() ?>sys/student/getAll" data-type="table" data-for="form[std_id]" data-id="std_id" >
                                        </div>

                                        <div class="form-group "  >
                                            <label for="reg_input">Class</label>
                                            <select id="class_tag"  name="form[cls_id]" class="form-control" disabled >
                                                <option> Class List </option>
                                            </select>
                                        </div>

                                        <div id="payment" class="hidden" >

                                            <table class="table table-bordered " >
                                                <tr>
                                                    <th>Total</th>
                                                    <td id="total" ></td>
                                                </tr>
                                                <tr>
                                                    <th>Paid </th>
                                                    <td id="paid" ></td>
                                                </tr>
                                            </table>

                                            <div class="form-group "  >
                                                <label for="reg_input">Amount</label>
                                                <input type="text" id="amount_tag" name="form[amount]" class="price form-control " >
                                            </div>
                                            <input type="hidden" id="std_id" name="form[std_id]" value="">
                                            <input type="hidden" id="std_cls_id" name="form[std_cls_id]" value="">
                                            <div class="form-group "  >
                                                <label for="reg_input">Type</label>
                                                <select name="form[type]" class="form-control" >
                                                    <option>Cash</option>
                                                    <option>Cheque</option>
                                                </select>
                                            </div>

                                        </div>



                                    </div>
                                </div>
                                <div class="row" id="subject_list" >

                                </div>
                                <div class="form-sep">
                                    <button class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- side navigation -->
    <?php $this->load->view('inc/nav') ?>

    <!-- right slidebar -->
    <div id="slidebar">
        <div id="slidebar_content">

        </div>
    </div>
</div>
<?php $this->load->view('inc/foot') ?>
<!-- datatables -->
<script src="<?= base_url() ?>assets/lib/DataTables/media/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>assets/lib/DataTables/media/js/dataTables.bootstrap.js"></script>
<script src="<?= base_url() ?>assets/lib/DataTables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>
<script src="<?= base_url() ?>assets/lib/DataTables/extensions/Scroller/js/dataTables.scroller.min.js"></script>
<!-- datatables functions -->
<script src="<?= base_url() ?>assets/js/apps/tisa_datatables.js"></script>

<!-- parsley.js validation -->
<script src="<?= base_url() ?>assets/lib/Parsley.js/dist/parsley.min.js"></script>
<!-- form validation functions -->
<script src="<?= base_url() ?>assets/js/apps/tisa_validation.js"></script>

<!-- iCheck -->
<script src="<?= base_url() ?>assets/lib/iCheck/jquery.icheck.min.js"></script>

<!-- masked inputs -->
<script src="<?= base_url() ?>assets/lib/jquery.inputmask/dist/jquery.inputmask.bundle.min.js"></script>

<script>

    $('#myModal').on('click','.tr-list',function(e){
        e.stopPropagation();
        var input_tag = $(".model-link");
        var obj = $(this).data('object');
        if ($("#" + input_tag.data('id')).length > 0)
            $("#" + input_tag.data('id')).val(obj.id);
        else {
            $('<input>').attr({
                type: 'hidden',
                id: input_tag.data('id'),
                name: input_tag.data('for'),
                value: obj.id
            }).appendTo('form');
        }
        $('#myModal').modal('hide');
        $("#Student_tag").val(obj.title);

        $.get(URL.base + "sys/student/getClassByStudentId", {std_id :obj.id },
            function (data) {
                $("#class_tag").html(data).removeAttr('disabled');
            }
        );
    });
    $("#class_tag").change(function(){
        var obj = $(this).find(':selected').data('object');
        $("#std_cls_id").val(obj.std_cls_id);
        $("#total").html(obj.fee);
        $("#paid").html(obj.amount);
        $("#amount_tag").data('max-value',(obj.fee -  obj.amount ));
        $("#payment").removeClass("hidden");
        maskedInputs.init();
    });



    //* masked inputs
    maskedInputs = {
        val:null,
        init: function () {
            if ($('.price').length) {
                $(".price").inputmask("decimal", {
                    radixPoint: ".",
                    groupSeparator: ",",
                    digits: 2,
                    autoGroup: true
                }).on('keydown',function(e){
                    this.val =$(this).val();
                }).on('keyup',function(){
                    v = $(this).val().replace(",", "");
                    if($(this).data('max-value') < v  )
                        $(this).val($(this).data('max-value'));
                });
            }
        }
    }

    // todo check inputs
    tisa_icheck = {
        init: function() {
            if($('.todo_section input:checkbox').length) {
                $('.todo_section input:checkbox').iCheck({
                    checkboxClass: 'icheckbox_minimal-green',
                    radioClass: 'iradio_minimal-green'
                });

                tisa_icheck.on_change();

                $('.todo_section input:checkbox').each(function() {
                    if ($(this).is(':checked')) {
                        $(this).closest('li').addClass('todo_checked');
                    }
                    tisa_icheck.check_state($(this));
                });
            }
        },
        on_change: function() {
            if($('.todo_section input:checkbox').length) {
                $('.todo_section input:checkbox').on('ifChecked', function(event){
                    $(this).closest('li').addClass('todo_checked');
                    tisa_icheck.check_state($(this));
                })
                    .on('ifUnchecked', function(event){
                        $(this).closest('li').removeClass('todo_checked');
                        tisa_icheck.check_state($(this));
                    })
            }
        },
        check_state: function(elem) {
            var checkedCount = $(elem).closest('.todo_section').find('.todo_checked').length;
            $(elem).closest('.todo_section').find('.td_resolved_tasks').text(checkedCount);
        }
    };


    ajax_cls_subject = {
        init : function(val){
            $.ajax({
                url : URL.base  + "sys/cls/getSubject/?cls_id="+val ,
                success : function(mge){
                    $('#subject_list').html(mge);
                }
            });

        }
    };


</script>

</body>


</html>
