<?php
/**
 * WHMCS Affiliate Coupons Admin Config Page
 *
 * @package    WHMCS 5.2.1+
 * @author     Myles McNamara (get@smyl.es)
 * @copyright  Copyright (c) Myles McNamara 2013-2014
 * @license    GPL v3+
 * @version    2.1a
 * @link       https://github.com/tripflex/whmcs-affcoupons
 * @Date:   2014-03-19 21:42:52
 * @Last Modified by:   Myles McNamara
 * @Last Modified time: 2014-03-23 01:43:07
 */

if ( ! defined( "WHMCS" ) ) {
	die( "This file cannot be accessed directly" );
}

if ( isset( $_REQUEST['cmd'] ) ) {
	switch ( $_REQUEST['cmd'] ) {
		case "del":
			$id = $_REQUEST['id'];
			delete_query( "tblaffcouponsconf", "id='$id'" );
			break;
		case "add":
			$label     = $_POST['label'];
			$type      = $_POST['type'];
			$recurring = $_POST['recurring'];
			$value     = $_POST['value'];
			$cyclestmp = $_POST['cycles'];
			foreach ( $cyclestmp as $v ) {
				if ( $acycles ) {
					$acycles = $acycles . ",$v";
				} else {
					$acycles = $v;
				}
			}
			$appliestotmp = $_POST['appliesto'];
			foreach ( $appliestotmp as $v ) {
				if ( $aappliesto ) {
					$aappliesto = $aappliesto . ",$v";
				} else {
					$aappliesto = $v;
				}
			}
			$expirationdate = $_POST['expirationdate'];
			$maxuses        = $_POST['maxuses'];
			$applyonce      = $_POST['applyonce'];
			$newsignups     = $_POST['newsignups'];
			$existingclient = $_POST['existingclient'];
			$r              = insert_query( "tblaffcouponsconf",
				array(
					"type"           => $type,
					"recurring"      => $recurring,
					"value"          => $value,
					"cycles"         => $acycles,
					"appliesto"      => $aappliesto,
					"expirationdate" => $expirationdate,
					"maxuses"        => $maxuses,
					"applyonce"      => $applyonce,
					"newsignups"     => $newsignups,
					"existingclient" => $existingclient,
					"label"          => $label
				) );
			break;
		case "edit":
			$label     = $_REQUEST['label'];
			$type      = $_REQUEST['type'];
			$recurring = $_REQUEST['recurring'];
			$value     = $_REQUEST['value'];
			$cyclestmp = $_REQUEST['cycles'];
			foreach ( $cyclestmp as $v ) {
				if ( $acycles ) {
					$acycles = $acycles . ",$v";
				} else {
					$acycles = $v;
				}
			}
			$appliestotmp = $_REQUEST['appliesto'];
			foreach ( $appliestotmp as $v ) {
				if ( $aappliesto ) {
					$aappliesto = $aappliesto . ",$v";
				} else {
					$aappliesto = $v;
				}
			}
			$expirationdate = $_REQUEST['expirationdate'];
			$maxuses        = $_REQUEST['maxuses'];
			$applyonce      = $_REQUEST['applyonce'];
			$newsignups     = $_REQUEST['newsignups'];
			$existingclient = $_REQUEST['existingclient'];
			update_query( "tblaffcouponsconf",
				array(
					"type"           => $type,
					"recurring"      => $recurring,
					"value"          => $value,
					"cycles"         => $acycles,
					"appliesto"      => $aappliesto,
					"expirationdate" => $expirationdate,
					"maxuses"        => $maxuses,
					"applyonce"      => $applyonce,
					"newsignups"     => $newsignups,
					"existingclient" => $existingclient,
					"label"          => $label
				), [ "id" => $_REQUEST['affcoupons_id'] ] );
			break;
	}
}
$products = array();
$result   = select_query( "tblproducts",
	"tblproducts.id,tblproducts.name,tblproductgroups.name AS groupname",
	"", "tblproductgroups`.`order` ASC,`tblproducts`.`order` ASC,`name", "ASC", "",
	"tblproductgroups ON tblproducts.gid=tblproductgroups.id" );
while ( $data = mysql_fetch_array( $result ) ) {
	$pid                       = $data["id"];
	$group                     = $data["groupname"];
	$prodname                  = $data["name"];
	$products[ $pid ]['group'] = $group;
	$products[ $pid ]['name']  = $prodname;
}
print "<style type=\"text/css\">
		#AddForm label.error {
			background:url(\"images/icons/accessdenied.png\") no-repeat 0px 0px;
			padding-left: 16px;
			padding-bottom: 2px;
			font-weight: bold;
			color: #EA5200;
		}
		</style>
        <script src=\"https://ajax.aspnetcdn.com/ajax/jquery.validate/1.5.5/jquery.validate.min.js\" type=\"text/javascript\"></script>
		<script type=\"text/javascript\">
		$().ready(function() {
			$(\"#AddForm\").validate({
				rules: {
					label: { required: true },
					value: { required: true, number: true },
					\"appliesto[]\": { required: true },
					maxuses: { required: true, number: true }
				},
				errorPlacement: function(error, element) {
					error.appendTo( element.parent().next() );
				},
				success: function(label) {
					label.html(\" \").addClass(\"error\");
				},
				submitHandler: function() {
					$(form).submit();
				}
			});
			$(\".affdatepick\").datepicker({
				dateFormat: \"yy-mm-dd\",
				showOn: \"button\",
				buttonImage: \"images/showcalendar.gif\",
				buttonImageOnly: true,
				showButtonPanel: true
			});
		});
		</script>	
		<form id=\"AddForm\" method=\"POST\" action=\"$modulelink&cmd=add\">
		<table class=\"form\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\" width=\"100%\">
		<tr><td width=\"25%\" class=\"fieldlabel\">Название:</td><td class=\"fieldarea\"><input type=\"text\" name=\"label\" size=\"25\"> Например \"Скидка 25%\"</td><td></td></tr>
		<tr><td class=\"fieldlabel\">Тип:</td><td class=\"fieldarea\">
		<select name=\"type\">
		<option value=\"Percentage\">Процент</option>
		<option value=\"Fixed Amount\">Фиксированная сумма</option>
		<option value=\"Free Setup\">Бесплатная установка</option>
		</select></td><td></td></tr>
		<tr><td class=\"fieldlabel\">Величина:</td><td class=\"fieldarea\"><input type=\"text\" name=\"value\" size=\"25\"></td><td></td></tr>
		<tr><td class=\"fieldlabel\">Продление:</td><td class=\"fieldarea\">
		<select name=\"recurring\">
		<option value=\"0\">Нет</option>
		<option value=\"1\">Да</option>
		</select></td><td></td></tr>
		<tr><td class=\"fieldlabel\">Платежные циклы</td><td class=\"fieldarea\">
		<select multiple name=\"cycles[]\" size=\"5\">
		<option value=\"\" selected>Любой срок оплаты</option>
		<option value=\"One Time\">Единоразово</option>
		<option value=\"Monthly\">Ежемесячно</option>
		<option value=\"Quarterly\">Ежеквартально</option>
		<option value=\"Semi-Annually\">Полугодично</option>
		<option value=\"Annually\">Ежегодно</option>
		<option value=\"Biennially\">Двухгодично</option>
		<option value=\"Triennially\">Трехгодично</option>
		</select></td><td></td></tr>
		<tr><td class=\"fieldlabel\">Применим для:</td><td class=\"fieldarea\">
		<select multiple name=\"appliesto[]\" size=\"5\">";
foreach ( $products as $k => $v ) {
	$pid      = $k;
	$group    = $v['group'];
	$prodname = $v['name'];
	print "<option value=\"$pid\">$group - $prodname</option>";
}
print "</select></td><td></td></tr>
		<tr><td class=\"fieldlabel\">Дата истечения:</td><td class=\"fieldarea\"><input type=\"text\" name=\"expirationdate\" class=\"affdatepick\"> Оставьте пустым, чтобы не ограничивать</td><td></td></tr>
		<tr><td class=\"fieldlabel\">Максимум применений:</td><td class=\"fieldarea\"><input type=\"text\" name=\"maxuses\" size=\"25\" value=\"0\"> Введите 0 для неограниченного применения</td><td></td></tr>
		<tr><td class=\"fieldlabel\">Один раз для заказа:</td><td class=\"fieldarea\">
		<select name=\"applyonce\">
		<option value=\"1\">Да</option>
		<option value=\"0\">Нет</option>
		</select> Применять только один раз для одного заказа (даже если подходит для нескольких его элементов)</td><td></td></tr>
		<tr><td class=\"fieldlabel\">Для новых регистраций:</td><td class=\"fieldarea\">
		<select name=\"newsignups\">
		<option value=\"1\">Да</option>
		<option value=\"0\">Нет</option>
		</select> Применять только для новых регистраций (не должно быть других активных заказов)</td><td></td></tr>
		<tr><td class=\"fieldlabel\">Для существующих клиентов:</td><td class=\"fieldarea\">
		<select name=\"existingclient\">
		<option value=\"0\">Нет</option>
		<option value=\"1\">Да</option>
		</select> Применять только для существующих клиентов (необходимо наличие активного заказа) </td><td></td></tr>
		<tr><td class=\"fieldlabel\" colspan=\"2\" align=\"center\"><button class=\"btn btn-success\" type=\"submit\" value=\"Add\">Добавить</button>  <button type=\"reset\" value=\"Reset\" class=\"btn btn-danger\">Сбросить форму</button></td><td></td><td></td></tr>
		</table></form>";
print "
		<div class=\"tablebg\">
		<table class=\"datatable\" cellspacing=\"1\" cellpadding=\"3\" width=\"100%\">
		<tr><th>Название</th><th>Тип</th><th>Величина</th><th>Продление</th><th>Платежные циклы</th><th>Применим для</th><th>Дата истечения</th><th>Максимум применений</th>
		<th>Один раз для заказа</th><th>Для новых регистраций</th><th>Для существующих клиентов</th><th>&nbsp;</th><th>&nbsp;</th></tr>";
$data = select_query( "tblaffcouponsconf", "*", array() );
while ( $r = mysql_fetch_array( $data ) ) {
	$id    = $r['id'];
	$label = $r['label'];
	$type  = $r['type'];
	$value = $r['value'];
	switch ( $r['recurring'] ) {
		case "1" :
			$recurring = "Да";
			break;
		case "0" :
			$recurring = "Нет";
			break;
		default :
			$recurring = "Нет";
			break;
	}
	if ( ! $r['cycles'] ) {
		$cycles = array( "Любой срок оплаты" );
	} else {
		$cycles = explode( ",", $r['cycles'] );
	}
	$appliestotmp = explode( ",", $r['appliesto'] );
	$appliesto    = array();
	foreach ( $appliestotmp as $v ) {
		$prodname    = $products[ $v ]['name'];
		$group       = $products[ $v ]['group'];
		$appliesto[] = "$group - $prodname";
	}
	if ( $r['expirationdate'] ) {
		$expirationdate = $r['expirationdate'];
	} else {
		$expirationdate = "Не ограничен";
	}
	$maxuses = $r['maxuses'];
	switch ( $r['applyonce'] ) {
		case "1" :
			$applyonce = "Yes";
			break;
		case "0" :
			$applyonce = "Нет";
			break;
		default :
			$applyonce = "Yes";
			break;
	}
	switch ( $r['newsignups'] ) {
		case "1" :
			$newsignups = "Да";
			break;
		case "0" :
			$newsignups = "Нет";
			break;
		default :
			$newsignups = "Да";
			break;
	}
	switch ( $r['existingclient'] ) {
		case "1" :
			$existingclient = "Да";
			break;
		case "0" :
			$existingclient = "Нет";
			break;
		default:
			$existingclient = "Да";
			break;
	}
	print "<tr>
			<td width=\"100\">$label</td><td width=\"100\">".translateType($type)."</td><td>".valueformat($value,$type)."</td><td>$recurring</td><td>";
	if ( count( $cycles ) > 1 ) {
		print "<select>";
		foreach ( $cycles as $v ) {

			print "<option value='$v'>" . translateCycles( $v ) . "</option>";
		}
		print "</select>";
	} else {
		print translateCycles( $cycles[0] );
	}
	print "</td><td>";
	if ( count( $appliesto ) > 1 ) {
		print "<select>";
		foreach ( $appliesto as $v ) {
			print "<option>$v</option>";
		}
		print "</select>";
	} else {
		print $appliesto[0];
	}

	print "</td><td width=\"100\">$expirationdate</td><td>$maxuses</td><td>$applyonce</td><td>$newsignups</td>
			<td>$existingclient</td><td><a href=\"$modulelink&cmd=del&id=$id\"><img src=\"images/icons/delete.png\"></a></td><td><a href='#' data-toggle=\"modal\" data-target=\"#editModal\" data-id='$id'><img src=\"images/edit.gif\"></a></td></tr>";
	$json_encode_data[ $id ] = [
		'expirationdate' => $expirationdate,
		'maxuses'        => $maxuses,
		'applyonce'      => $r['applyonce'],
		'newsignups'     => $r['newsignups'],
		'existingclient' => $r['existingclient'],
		'appliesto'      => $appliestotmp,
		'cycles'         => $cycles,
		'recurring'      => $r['recurring'],
		'value'          => $value,
		'type'           => $type,
		'label'          => $label,
	];
}
print "</table><br /></div>";
print "
<div id='json_data' style='display: none'>" . json_encode( $json_encode_data ) . "</div>
<!-- Trigger the modal with a button -->
<!-- Modal -->
<div id=\"editModal\" class=\"modal fade\" role=\"dialog\">
  <div class=\"modal-dialog\">

    <!-- Modal content-->
    <div class=\"modal-content\">
      <div class=\"modal-header\">
        <button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>
        <h4 class=\"modal-title\"></h4>
      </div>
      <div class=\"modal-body\">
       ";
print"
<form id=\"EditForm\" method=\"POST\" action=\"$modulelink&cmd=edit\">
		<table class=\"form\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\" width=\"100%\">
		<input type='hidden' name='affcoupons_id' value=''>
		<tr><td width=\"25%\" class=\"fieldlabel\">Название:</td><td class=\"fieldarea\"><input type=\"text\" name=\"label\" size=\"25\"> Например \"Скидка 25%\"</td><td></td></tr>
		<tr><td class=\"fieldlabel\">Тип:</td><td class=\"fieldarea\">
		<select name=\"type\">
		<option value=\"Percentage\">Процент</option>
		<option value=\"Fixed Amount\">Фиксированная сумма</option>
		<option value=\"Free Setup\">Бесплатная установка</option>
		</select></td><td></td></tr>
		<tr><td class=\"fieldlabel\">Величина:</td><td class=\"fieldarea\"><input type=\"text\" name=\"value\" size=\"25\"></td><td></td></tr>
		<tr><td class=\"fieldlabel\">Продление:</td><td class=\"fieldarea\">
		<select name=\"recurring\">
		<option value=\"0\">Нет</option>
		<option value=\"1\">Да</option>
		</select></td><td></td></tr>
		<tr><td class=\"fieldlabel\">Платежные циклы</td><td class=\"fieldarea\">
		<select multiple name=\"cycles[]\" size=\"5\">
		<option value=\"Any Payment Term\">Любой срок оплаты</option>
		<option value=\"One Time\">Единоразово</option>
		<option value=\"Monthly\">Ежемесячно</option>
		<option value=\"Quarterly\">Ежеквартально</option>
		<option value=\"Semi-Annually\">Полугодично</option>
		<option value=\"Annually\">Ежегодно</option>
		<option value=\"Biennially\">Двухгодично</option>
		<option value=\"Triennially\">Трехгодично</option>
		</select></td><td></td></tr>
		<tr><td class=\"fieldlabel\">Применим для:</td><td class=\"fieldarea\">
		<select multiple name=\"appliesto[]\" size=\"5\">";
foreach ( $products as $k => $v ) {
	$pid      = $k;
	$group    = $v['group'];
	$prodname = $v['name'];
	print "<option value=\"$pid\">$group - $prodname</option>";
}
print "</select></td><td></td></tr>
		<tr><td class=\"fieldlabel\">Дата истечения:</td><td class=\"fieldarea\"><input type=\"text\" name=\"expirationdate\" class=\"affdatepick\"> Оставьте пустым, чтобы не ограничивать</td><td></td></tr>
		<tr><td class=\"fieldlabel\">Максимум применений:</td><td class=\"fieldarea\"><input type=\"text\" name=\"maxuses\" size=\"25\" value=\"0\"> Введите 0 для неограниченного применения</td><td></td></tr>
		<tr><td class=\"fieldlabel\">Один раз для заказа:</td><td class=\"fieldarea\">
		<select name=\"applyonce\">
		<option value=\"1\">Да</option>
		<option value=\"0\">Нет</option>
		</select> Применять только один раз для одного заказа (даже если подходит для нескольких его элементов) </td><td></td></tr>
		<tr><td class=\"fieldlabel\">Для новых регистраций:</td><td class=\"fieldarea\">
		<select name=\"newsignups\">
		<option value=\"1\">Да</option>
		<option value=\"0\">Нет</option>
		</select> Применять только для новых регистраций (не должно быть других активных заказов) </td><td></td></tr>
		<tr><td class=\"fieldlabel\">Для существующих клиентов:</td><td class=\"fieldarea\">
		<select name=\"existingclient\">
		<option value=\"0\">Нет</option>
		<option value=\"1\">Да</option>
		</select> Применять только для существующих клиентов (необходимо наличие активного заказа) </td><td></td></tr>
		<tr><td></td><td></td></tr>
		</table></form>";
print"
      </div>
      <div class=\"modal-footer\">
        <button type=\"button\" class=\"btn btn-success\" onclick='document.getElementById(\"EditForm\").submit();' data-dismiss=\"modal\">Сохранить</button>
      </div>
    </div>

  </div>
</div>
";

print "<script>
$('#editModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget); // Button that triggered the modal
  var id = button.data('id'); // Extract info from data-* attributes
  console.log('affcoupons->'+id);
  var json = jQuery.parseJSON($(\"#json_data\").text());
  console.log(json);
  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
  var modal = $(this);
  modal.find('.modal-title').text('Редактирование купона \"' + json[id].label+'\"');
  modal.find(\"input[name='affcoupons_id']\").val(id);
  modal.find(\"input[name='label']\").val(json[id].label);
  modal.find(\"select[name='type']\").val(json[id].type);
  modal.find(\"input[name='value']\").val(json[id].value);
  modal.find(\"select[name='recurring']\").val(json[id].recurring);
  
  $.each(modal.find(\"select[name='cycles[]'] > option\"), function(i,e){
	 $(e).prop(\"selected\", false);
  });

	$.each(json[id].cycles, function(i,e){
    	console.log(\"select[name='cycles[]'] > option[value='\"+e+\"']\");
      	modal.find(\"select[name='cycles[]'] > option[value='\"+e+\"']\").prop(\"selected\", true);
	});

  	$.each(modal.find(\"select[name='appliesto[]'] > option\"), function(i,e){
	 $(e).prop(\"selected\", false);
  	});
  	
	$.each(json[id].appliesto, function(i,e){
    	console.log(\"select[name='appliesto[]'] > option[value='\"+e+\"']\");
      	modal.find(\"select[name='appliesto[]'] > option[value='\"+e+\"']\").prop(\"selected\", true);
	});
    
    if(json[id].expirationdate == 'Не ограничен'){
   	modal.find(\"input[name='expirationdate']\").val('');
	} else {
         modal.find(\"input[name='expirationdate']\").val(json[id].expirationdate);
	}
	
  modal.find(\"input[name='maxuses']\").val(json[id].maxuses);
  
  modal.find(\"select[name='applyonce']\").val(json[id].applyonce);
  modal.find(\"select[name='newsignups']\").val(json[id].newsignups);
  modal.find(\"select[name='existingclient']\").val(json[id].existingclient);
})
</script>";

function translateType($type){
	switch ( $type ) {
		case "Percentage":
			return 'Процент';
			break;
		case "Fixed Amount":
			return 'Сумма';
			break;
		case "Free Setup":
			return 'Бесплатная установка';
			break;
		default:
			return $type;
			break;
	}
}
function translateCycles( $cycles ) {
	switch ( $cycles ) {
		case "Any Payment Term":
			return "Любой";
			break;
		case "One Time":
			return 'Единоразово';
			break;
		case "Monthly":
			return 'Ежемесячно';
			break;
		case "Quarterly":
			return 'Ежеквартально';
			break;
		case "Semi-Annually":
			return 'Полугодично';
			break;
		case "Annually":
			return 'Ежегодно';
			break;
		case "Biennially":
			return 'Двухгодично';
			break;
		case "Triennially":
			return 'Трехгодично';
			break;
		default:
			return $cycles;
			break;
	}
}
function valueformat($val,$type){
	switch ( $type ) {
		case "Percentage":
			return $val.'%';
			break;
		case "Fixed Amount":
			return $val;
			break;
		case "Free Setup":
			return '-';
			break;
		default:
			return $type;
			break;
	}
}