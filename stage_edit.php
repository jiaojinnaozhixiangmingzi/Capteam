<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session.php'); ?>
<?php


$colname_Recordset1 = "-1";
if (isset($_GET['editID'])) {
  $colname_Recordset1 = $_GET['editID'];
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$selStage = "SELECT * FROM tk_stage WHERE stageid = $colname_Recordset1";
  mysql_select_db($database_tankdb, $tankdb);
  $Result1 = mysql_query($selStage, $tankdb) or die(mysql_error());
  $row = mysql_fetch_array($Result1);
  $stage_title = $row['tk_stage_title'];
  $stage_desc = $row['tk_stage_desc'];
  $stage_st = $row['tk_stage_st'];
  $stage_et = $row['tk_stage_et'];

  $title = "-1";
    if (isset($_POST['tk_stage_title'])) {
      $title= $_POST['tk_stage_title'];
    }

    $description = "-1";
    if (isset($_POST['tk_stage_desc'])) {
      $description = $_POST['tk_stage_desc'];
    }

    $st_time = "-1";
    if (isset($_POST['stage_start'])) {
      $st_time= $_POST['stage_start'];
    }

    $en_time = "-1";
    if (isset($_POST['stage_end'])) {
      $en_time= $_POST['stage_end'];
    }
/*if ( empty( $_POST['stage_text'] ) ){
$project_text = "$";
}else{
$project_text = sprintf("stage_text=%s,", GetSQLValueString(str_replace("%","%%",$_POST['project_text']), "text"));
}

if ( empty( $_POST['stage_start'] ) ){
$project_start = "stage_start='0000-00-00',";
}else{
$project_start = sprintf("stage_start=%s,", GetSQLValueString($_POST['stage_start'], "date"));
}

if ( empty( $_POST['stage_end'] ) ){
$project_end = "stage_end='0000-00-00',";
}else{
$project_end = sprintf("stage_end=%s,", GetSQLValueString($_POST['stage_end'], "date"));
}*/

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  //把当前时间作为最后一次修改时间
  $update_stage_lastupdate = date("Y-m-d H:i:s",time());
  //更新数据库
  $updateSQL = sprintf("UPDATE tk_stage SET tk_stage_title=%s, tk_stage_desc=%s, 
    tk_stage_st=%s, tk_stage_et=%s,tk_stage_lastupdate='$update_stage_lastupdate' WHERE stageid=$colname_Recordset1",
            GetSQLValueString($_POST['tk_stage_title'],"text"),
            GetSQLValueString($_POST['tk_stage_desc'],"text"),
            GetSQLValueString($_POST['stage_start'],"text"),
            GetSQLValueString($_POST['stage_end'],"text"));

  mysql_select_db($database_tankdb, $tankdb);
  $Result2 = mysql_query($updateSQL, $tankdb) or die(mysql_error());

  $updateGoTo = "stage_view.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}


/*mysql_select_db($database_tankdb, $tankdb);
$query_Recordset1 = sprintf("SELECT * FROM tk_project WHERE id = %s", GetSQLValueString($colname_Recordset1, "int"));
$Recordset1 = mysql_query($query_Recordset1, $tankdb) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

$user_arr = get_user_select($colname_Recordset1);*/

?>

<?php require( 'head.php'); ?>
<link href="skin/themes/base/lhgcheck.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="srcipt/lhgcore.js"></script>
<script type="text/javascript" src="srcipt/lhgcheck.js"></script>
<link rel="stylesheet" href="bootstrap/css/bootstrap-multiselect.css" type="text/css" />
<script type="text/javascript" src="bootstrap/js/bootstrap-multiselect.js"></script>
<link rel="stylesheet" href="bootstrap/css/datepicker3.css" type="text/css" />
<script type="text/javascript" src="bootstrap/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="bootstrap/js/locales/bootstrap-datepicker.zh-CN.js"></script>

<script type="text/javascript">
    $(function () {
        $('#datepicker').datepicker({
            format: "yyyy-mm-dd" <? php
            if ($language == "cn") {
                echo ", language: 'zh-CN'";
            } ?>
        });
        $('#datepicker2').datepicker({
            format: "yyyy-mm-dd" <? php
            if ($language == "cn") {
                echo ", language: 'zh-CN'";
            } ?>
        });
    });
</script>
<script type="text/javascript">
    J.check.rules = [
        {
            name: 'tk_stage_title',
            mid: 'tk_stage_title_msg',
            type: '',
            requir: true,
            //min: 2,
            //max: 32,
            warn: '<?php echo $multilingual_projectstatus_titlerequired; ?>'
        },
        {
            name: 'datepicker',
            mid: 'datepicker_msg',
            requir: true,
            type: 'date',
            warn: '<?php echo $multilingual_error_date; ?>'
        },
        {
            name: 'datepicker2',
            mid: 'datepicker2_msg',
            requir: true;
            type: 'date',
            warn: '<?php echo $multilingual_error_date; ?>'
        }

];

    window.onload = function () {
            J.check.regform('form1');
        }
        //function option_gourl(str)
        //{
        //if(str == '-1')window.open('user_add.php');
        //if(str == '-2')window.open('project_status.php');
        //}
</script>
<script charset="utf-8" src="editor/kindeditor.js"></script>
<script charset="utf-8" src="editor/lang/zh_CN.js"></script>
<script>
    var editor;
    KindEditor.ready(function (K) {
        editor = K.create('#tk_stage_desc', {
            width: '100%',
            height: '350px',
            items: [
        'source', '|', 'undo', 'redo', '|', 'preview', 'print', 'template', 'cut', 'copy', 'paste',
        'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
        'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
        'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/',
        'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
        'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image',
        'flash', 'media', 'insertfile', 'table', 'hr', 'map', 'code', 'pagebreak', 'anchor',
        'link', 'unlink', '|', 'about'
]
        });
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#select2').multiselect({

            enableCaseInsensitiveFiltering: true,
            maxHeight: 360,
            filterPlaceholder: '<?php echo $multilingual_user_filter; ?>'
        });
    });
</script>

<form action="<?php echo $editFormAction; ?>" method="post" name="myform" id="form1">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>

            <!-- 左边20%的宽度的树或者说明  -->
            <td width="20%" class="input_task_right_bg" valign="top">
                <table width="90%" border="0" cellspacing="0" cellpadding="0" align="center">
                    <tr>
                        <td valign="top" class="gray2">
                            <h4 style="margin-top:40px; margin-left: 5px;"><strong><?php echo $multilingual_stage_view_nowbs; ?></strong></h4>
                            <p>
                                <?php echo $multilingual_stage_add_text; ?>
                            </p>

                        </td>
                    </tr>
                </table>
            </td>

            <!-- 右边80%宽度的主体内容 -->
            <td width="80%" valign="top">
                <table width="98%" border="0" cellspacing="0" cellpadding="5" align="center">
                    <tr>
                        <td>
                            <div class="col-xs-12">
                                <h3><?php echo $multilingual_stage_edit; ?></h3>
                            </div>

                            <!-- 阶段名称 -->
                            <div class="form-group col-xs-12">
                                <label for="tk_stage_title">
                                    <?php echo $multilingual_stage_title; ?><span id="tk_stage_title_msg"></span>
                                </label>
                                <div>
                                    <input type="text" name="tk_stage_title" id="tk_stage_title" value="
                                                <?php if($title!=-1)
                                                            {echo $title;}
                                                      else{echo $stage_title;}
                                                ?>" 
                                                class="form-control" placeholder="<?php echo $multilingual_stage_title_tips; ?>" >
                                                <span class="help-block"><?php echo $multilingual_default_stage_title_tips; ?></span>
                                </div>
                            </div>

                            <!-- 阶段描述 -->
                            <div class="form-group col-xs-12">
                                <label for="tk_stage_desc">
                                    <?php echo $multilingual_stage_description; ?><span id="tk_stage_title_msg"></span>
                                </label>
                                <div>
                                    <textarea name="tk_stage_desc" id="tk_stage_desc">
                                        <!--<?php echo htmlentities($row_Recordset1[ 'text'], ENT_COMPAT, 'utf-8'); ?>-->
                                        <?php  if($description!=-1)
                                                    {echo $description;}
                                                else
                                                    {echo $stage_desc;}
                                                     //echo $stage_title;
                                        ?>
                                    </textarea>
                                </div>
                            </div>

                            <!-- 起始时间 -->
                            <div class="form-group col-xs-12">
                                <label for="datepicker">
                                    <?php echo $multilingual_default_task_planstart; ?><span id="datepicker_msg"></span>
                                </label>
                                <div>
                                    <input type="text" name="stage_start" id="datepicker" value=
                                    "<?php if($st_time == -1)
                                                {echo $stage_st;}
                                            else 
                                                {echo $st_time;}
                                     ?>" class="form-control" />
                                </div>
                            </div>

                            <!-- 结束时间 -->
                            <div class="form-group col-xs-12">
                                <label for="datepicker2">
                                    <?php echo $multilingual_default_task_planend; ?><span id="datepicker2_msg"></span>
                                </label>
                                <div>
                                    <input type="text" name="stage_end" value=
                                    "<?php if($en_time == -1)
                                                {echo $stage_et;}
                                            else
                                                {echo $en_time;}
                                    ?>" id="datepicker2" class="form-control" />
                                </div>
                            </div>

                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr class="input_task_bottom_bg">
            <td></td>
            <td height="50px">

                <!-- 提交按钮 -->
                <button type="submit" class="btn btn-primary btn-sm submitbutton" name="cont">
                    <?php echo $multilingual_global_action_save; ?>
                </button>
                <button type="button" class="btn btn-default btn-sm" onClick="javascript:history.go(-1);">
                    <?php echo $multilingual_global_action_cancel; ?>
                </button>


                <input type="hidden" name="MM_update" value="form1" />
                <input type="hidden" name="id" value="<?php echo $colname_Recordset1; ?>" />
            </td>
        </tr>
    </table>
</form>
<?php require( 'foot.php'); ?>
</body>

</html>
<?php mysql_free_result($Recordset1); mysql_free_result($Recordset2); ?>