// import { init } from './main.js';

function twoOrThreeD(checked) {
    let lable = document.getElementById("text-checkbox");
    // console.log(checked);
    if (checked === true) {
        lable.innerText = "3D";
        // init();

    }
    else {
        lable.innerText = "2D";
    }
};