function validate_form() {
    valid = true;

    if (document.form_check.material.value == "") {
        alert("Введите материал");
        valid = false;
        document.form_check.material.focus();
    }
    if (document.form_check.density.value == "") {
        alert("Введите плотность");
        valid = false;
        document.form_check.density.focus();
    }
    if (document.form_check.thermal_cond.value == "") {
        alert("Введите теплоемкость");
        valid = false;
        document.form_check.thermal_cond.focus();
    }
    if (document.form_check.heat_capacity.value == "") {
        alert("Введите теплопроводимость");
        valid = false;
        document.form_check.heat_capacity.focus();
    }
    if (document.form_check.temp.value == "") {
        alert("Введите температуру");
        valid = false;
        document.form_check.temp.focus();
    }
    if (document.form_check.e.value == "") {
        alert("Введите степень черноты");
        valid = false;
        document.form_check.e.focus();
    }

    return valid;
}

