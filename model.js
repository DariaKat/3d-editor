function colorWall() { // изменение цвета конструкции
    let timeR2 = document.getElementById('time_r2');
    let colorBox = timeR2.value; // значение range
    let color = 0;

    timeR2.style.background = `linear-gradient(to right, rgb( ${timeR2.min} , 0,  ${(255 - timeR2.min)}), rgb(${timeR2.max}, 0, ${(255 - timeR2.max)}))`;

    let apr = Number(document.getElementById('apr').innerText);
    let k = Number(document.getElementById('k').innerText);
    let bi = Number(document.getElementById('bi').innerText);
    let a1 = Number(document.getElementById('a1').innerText);
    let tn = Number(document.getElementById('tn').innerText);
    let mu1 = Number(document.getElementById('mu1').innerText);
    let thickeness = Number(document.getElementById('th2').innerText);
    let b = Math.pow((thickeness + k * Math.sqrt(apr)), 2) / (mu1 * mu1 * apr);
    let t = 2.3 * b * Math.log10(a1/(((colorBox - tn)/(1250-tn))-(1/(1+bi)))); // расчет температуры в зависимости от времени

    document.getElementById('inputTemp').value = colorBox; 
    document.getElementById('inputTemp').style.width = "50px"; 

    document.getElementById('inputTime').value = t.toFixed(2); 
    document.getElementById('inputTime').style.width = "50px"; 
    if (colorBox > 255) { color = 255 }
    color = parseInt(colorBox);
    let colorWall = 'rgb(' + (color) + ', 0, ' + (255 - color) + ')'; // цвет 
    return colorWall;
}



function init() {
    const renderer = new THREE.WebGLRenderer(); //задали рендер

    renderer.setSize(window.innerWidth / 2, window.innerHeight / 2); // задали длину и ширину всего экрана
    const convas = document.body.appendChild(renderer.domElement); // создали элемент
    convas.style.display = "inlain-block";

    const scene = new THREE.Scene(); // объявление сцены
    scene.background = new THREE.Color(0xffffff);

    scene.add(new THREE.AmbientLight(0x111111));  //равномерный источник света

    const directionalLight = new THREE.DirectionalLight(0xffffff, 1); // источник света 1
    directionalLight.position.set(-1, 2, 40).normalize();
    scene.add(directionalLight);

    const directionalLight_2 = new THREE.DirectionalLight(0xffffff, 0.5); // источник света 2
    directionalLight_2.position.set(10, 10, -40).normalize();
    scene.add(directionalLight_2);

    const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000); // объявление камеры
    camera.position.y = 5;
    camera.position.z = 10;

    const controls = new THREE.OrbitControls(camera, renderer.domElement); // для обзора с помощью мыши 

    const grid = new THREE.GridHelper( 100, 40, 0x000000, 0x000000 ); // отображение сетки
	grid.material.opacity = 0.1;
	grid.material.depthWrite = false;
	grid.material.transparent = true;
	scene.add( grid );

    const geometry = new THREE.BoxGeometry(10, 6, 1); // фигура

    const material = new THREE.MeshPhongMaterial({
        color: colorWall(),
        metalness: 0,
        roughness: 0.5,
    }); // материал фигуры, цвет фигуры и тд.

    const cube = new THREE.Mesh(geometry, material); // создание куба

    cube.position.set(0, 3, 0);
    
    scene.add(cube); // добовление на сцену куба
  

    function animate() { //отрисовка
        requestAnimationFrame(animate);
        // material.needsUpdate = true;
        material.color.set( colorWall() );
        renderer.render(scene, camera);
        controls.update();
    }
    
    animate();
}

init();