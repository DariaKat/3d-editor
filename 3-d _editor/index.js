// function init() {

  let plane;
  let pointer,
    raycaster;
  let cubeGeo, cubeMaterial;
  const objects = [];
  let layer = [];
  let objFire = [];
  let gltfObj = [];

  const canvas = document.getElementById("canvas");

  let clock = new THREE.Clock();

  // Подключение сцены
  let scene = new THREE.Scene();
  scene.background = new THREE.Color(0xffffff);

  // Подключение света
  const ambientLight = new THREE.AmbientLight(0x606060);
  scene.add(ambientLight);

  const directionalLight = new THREE.DirectionalLight(0xffffff);
  directionalLight.position.set(1, 0.75, 0.5).normalize();
  scene.add(directionalLight);

  //Размер canvas
  const sizes = {
    width: window.innerWidth,
    height: window.innerHeight,
  };

  //Рендер
  let renderer = new THREE.WebGLRenderer({
    canvas: canvas,
  });
  renderer.setSize(sizes.width, sizes.height);
  renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));

  //Установка камеры
  let camera = new THREE.PerspectiveCamera(
    45,
    sizes.width / sizes.height,
    1,
    10000
  );
  camera.position.x = 500;
  camera.position.y = 800;
  camera.position.z = 1300;
  scene.add(camera);

  // Передвижение по canvas

  let material = document.getElementById("material");
  material.addEventListener('click', () => console.log(material.value));


  let orbit = new THREE.OrbitControls(camera, renderer.domElement);
  //не возможность вниз передвигаться (не знаю уместно или нет)
  orbit.maxPolarAngle = 0.9 * Math.PI / 2;
  orbit.update();
  orbit.addEventListener("change", animate);

  let control = new THREE.TransformControls(camera, renderer.domElement);
  control.addEventListener("change", animate);

  control.addEventListener("dragging-changed", function (event) {
    orbit.enabled = !event.value;
  });

  //Сетка
  const grid = new THREE.GridHelper(5000, 100, 0x000000, 0x000000);
  grid.material.opacity = 0.1;
  grid.material.depthWrite = false;
  grid.material.transparent = true;
  scene.add(grid);

  raycaster = new THREE.Raycaster();
  pointer = new THREE.Vector2();

  //плоскость на которую ставятся предметы
  const geometry = new THREE.PlaneGeometry(10000, 10000);
  geometry.rotateX(-Math.PI / 2);
  plane = new THREE.Mesh(
    geometry,
    new THREE.MeshBasicMaterial({ visible: false })
  );
  scene.add(plane);
  objects.push(plane);


  const wall = document.getElementById("wall");
  wall.addEventListener("click", (e) => {
    document.addEventListener("pointerdown", onPointerMove(e, "wall"));
  });

  const fire = document.getElementById("fire");
  fire.addEventListener("click", (e) => {
    document.addEventListener("pointerdown", onPointerMove(e, "fire"));
  });

  const glass = document.getElementById("window");
  glass.addEventListener("click", (e) => {
    document.addEventListener("pointerdown", onPointerMove(e, "window"));
  });

  const door = document.getElementById("door");
  door.addEventListener("click", (e) => {
    document.addEventListener("pointerdown", onPointerMove(e, "door"));
  });

  const table = document.getElementById("table");
  table.addEventListener("click", (e) => {
    document.addEventListener("pointerdown", onPointerMove(e, "table"));
  });

  const chair = document.getElementById("chair");
  chair.addEventListener("click", (e) => {
    document.addEventListener("pointerdown", onPointerMove(e, "chair"));
  });

  const kitchen = document.getElementById("kitchen");
  kitchen.addEventListener("click", (e) => {
    document.addEventListener("pointerdown", onPointerMove(e, "kitchen"));
  });
  
  let img = document.getElementById("buttonPlayorPause");

  let mixer;

//начать анимацию огня
function playFire() {
  if (objFire.length > 0) {
    img.setAttribute("src", "../img/pause.svg");
    for (let k in objFire) {
      scene.remove(control);
      mixer = new THREE.AnimationMixer(objFire[k]);
      mixer.clipAction(gltfObj[k].animations[0]).play();
      updateFire();
    }
   
  }
  else {
    alert("Поставьте очаг воспламенения!")
  }
}

//поставить на паузу анимацию огня
function pauseFire() {
  if (objFire.length > 0) {
    img.setAttribute("src", "../img/pause.svg");
    for (let k in objFire) {
      mixer = new THREE.AnimationMixer(objFire[k]);
      mixer.clipAction(gltfObj[k].animations[0]);
      img.setAttribute("src", "../img/play.svg");
      updateFire();
    }
  }

}


  //для анимации огня 
  let buttonPlay = document.getElementById("playFire");
  buttonPlay.addEventListener("click", (e) => {
   
    if (img.getAttribute("src") === "../img/play.svg") {
      playFire();
    }
    else {
      pauseFire();
    }
  });

//удаление элемента со сцены 
  function deleteElement(layer, idButton) {
    for (let i in layer) {
      console.log(layer[i].uuid);
      if (layer[i].uuid == idButton) {
        var selectedObject = scene.getObjectByProperty('uuid', layer[i].uuid);
        scene.remove(selectedObject);
        scene.remove(control);
        layer.splice(i,1);
        gltfObj.splice(i,1);
        objFire.splice(i,1);
        animate();
      }
    }
  };

  //редактирование элемента
  const editLayers = (layer, idButton) => {
    for (let i in layer) {
      console.log(layer[i].uuid);
      if (layer[i].uuid == idButton) {
        var selectedObject = scene.getObjectByProperty('uuid', layer[i].uuid);
        scene.add(control);
        control.attach(selectedObject);
        
        animate();
      }
    }
  };


  // layerButton.classList.add("btn btn-outline-secondary");
let indexLayer=1;

  //функция для написания на панели слоев при создании элементов 
  function createLayers(nameModel, uuid, layer) {
    let layerElements = document.getElementById("render-figures");
    let layerButton = document.createElement("div");
    let nameElementLayer = document.createElement("input");
    let trashElement = document.createElement("button");
    let editElement = document.createElement("button");
    layerButton.classList.add('layerButton');
    trashElement.id = `${uuid}`;
    trashElement.classList.add('trashElement');
    editElement.classList.add('editElement');

    nameElementLayer.value = `${nameModel + " " +indexLayer}`;
    nameElementLayer.setAttribute("readonly", true);
  
    indexLayer++;

    layerElements.appendChild(layerButton);
    layerButton.appendChild(nameElementLayer);
  
    layerButton.appendChild(editElement);
    layerButton.appendChild(trashElement);
 
    trashElement.onclick = function () {
      deleteElement(layer, uuid);
      layerButton.remove();
    }

    editElement.onclick = function () {
      editLayers(layer, uuid);
      nameElementLayer.removeAttribute("readonly");
    }

  }

  function clickPress(event) {
    if (event.keyCode == 13) {
      console.log("click");
    }
}


  function onPointerMove(e, nameModel) {
    pointer.set(
      (e.clientX / window.innerWidth) * 2 - 1,
      -(e.clientY / window.innerHeight) * 2 + 1
    );
    raycaster.setFromCamera(pointer, camera);

    const intersects = raycaster.intersectObjects(objects, true);

    if (intersects.length > 0) {
      const intersect = intersects[0];
    
      //Создание объекта
      
        if (nameModel === 'wall') { //Выбрана ли стена
          if (material.value == "none") { //Проверка выбран ли материал
            alert('Выберите материал!');
          }
          else {
            let cubeGeo = new THREE.BoxGeometry(500, 300, 15);
            const texture = new THREE.TextureLoader().load(`./texture/${material.value}.jpg`);
            let cubeMaterial = new THREE.MeshLambertMaterial({ color: 0xffffff, map: texture });
            const voxel = new THREE.Mesh(cubeGeo, cubeMaterial);
            scene.add(voxel); //Добавление элемента на сцену
            objects.push(voxel);
            layer.push(voxel);//Добавление элемента в объект
            createLayers(nameModel, voxel.uuid, layer);
            scene.add(control);
            if (voxel !== control.object) {
              control.attach(voxel);
            }
          }
        }
        else if (nameModel === 'fire') { //Добавление элемента огня на сцену
          let loader = new THREE.GLTFLoader();
          loader.load(
            `./3d-model/3d-model-fire/scene.gltf`,
            function (gltf) {
              gltf.scene.scale.set(5, 5, 5);
              scene.add(gltf.scene);
              layer.push(gltf.scene);
              objFire.push(gltf.scene);
              gltfObj.push(gltf);
              gltf.scene.traverse(function (object) {
                if (object.isMesh) objects.push(object);
              });
              createLayers(nameModel, gltf.scene.uuid, layer);
              scene.add(control);
              if (gltf.scene !== control.object) {
                control.attach(gltf.scene);
              }
            }
          );
        }
        else { //Добавление любого другого элемента на сцену
          let loader = new THREE.GLTFLoader();
          loader.load(
            `./3d-model/3d-model-${nameModel}/scene.gltf`,
            function (gltf) {
          
              scene.add(gltf.scene);
              layer.push(gltf.scene);
              gltf.scene.traverse(function (object) {
                if (object.isMesh) objects.push(object);
              });
              createLayers(nameModel, gltf.scene.uuid, layer);
              scene.add(control);
              if (gltf.scene !== control.object) {
                control.attach(gltf.scene);
              }
            }
          );
        }
      animate();
      console.log(layer);
    }
  }

  window.addEventListener("keydown", keyEventCode);


  //функция при нажатии на клавиши пермещать, перетаскивать, увеличивать предмет
  function keyEventCode(event) {
    switch (event.keyCode) {
      case 87: // W
        control.setMode("translate");
        break;

      case 69: // E
        control.setMode("rotate");
        break;

      case 82: // R
        control.setMode("scale");
        break;
      
    }
  }

function updateFire() {
  const delta = clock.getDelta();
  mixer.update(delta);
  requestAnimationFrame(updateFire);
}

  // Анимация
function animate() {
    renderer.render(scene, camera);
    window.requestAnimationFrame(animate);
  }
  animate();
// }

// export default init;