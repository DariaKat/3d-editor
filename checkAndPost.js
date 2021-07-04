function CheckForm()
{
	console.log('CheckForm');
    var is_filled = true;
    
    if (document.getElementById('thickeness').value == '') {
        is_filled = false;
        alert("Введите толщину");
        document.getElementById('thickeness').focus();
    }

    if (document.getElementById('mass').value == '') {
        is_filled = false;
        alert("Введите объём массы");
        document.getElementById('mass').focus();
    }

    if (document.getElementById('moisture').value == '') {
        is_filled = false;
        alert("Введите начальную весовую влажность");
        document.getElementById('moisture').focus();
    }
	

    if (document.getElementById('inittemp').value == '') {
        is_filled = false;
        alert("Введите температуру");
        document.getElementById('inittemp').focus();
    }

	if (document.getElementById('temperature').value == '') {
        is_filled = false;
        alert("Введите температуру");
        document.getElementById('temperature').focus();
    }

    if (document.getElementById('coeftemp').value == '') {
        is_filled = false;
        alert("Введите температуру");
        document.getElementById('coeftemp').focus();
    }
   

    if (is_filled == true) {
        //alert(is_filled, is_checked, is_selected);
		return true;
    }
    else {
        return false;
    }	
}

$(document).ready(function () {
    var TrOrFl;
	//отправка запроса на сервер
    $('#button_add').click(function () {
        TrOrFl = CheckForm();//Проверка формы на заполнение
        if (TrOrFl) {
            console.log('Нажата кнопка add');
            let regData = {
                            material: $("#material").val(),
                            thickeness: $("#thickeness").val(),
                            mass: $("#mass").val(),
                            moisture: $("#moisture").val(),
                            temperature: $("#temperature").val(),
                            inittemp: $("#inittemp").val(),
                            coeftemp: $("#coeftemp").val(),
                        };
            $.post(
                "add_to_bd.php",
                regData,
                function (data) {
                    console.log(data);
                    $('#result_sql').html(data);
                }
            );
                
			}
        });   
});
