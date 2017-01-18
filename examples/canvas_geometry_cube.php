<!DOCTYPE html>
<html lang="en">
<head>
    <title>three.js canvas - geometry - cube</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <style>
        body {
            font-family: Monospace;
            background-color: #f0f0f0;
            margin: 0px;
            overflow: hidden;
        }
    </style>
</head>
<body>
<div id="container" style="width: 100%; height: 600px; border: 1px solid red;"></div>
<div class="side-selector" id="side-selector">
    <button class="box-side front">front</button>
    <button class="box-side left ">left</button>
    <button class="box-side back ">back</button>
    <button class="box-side right ">right</button>
    <button class="box-side top ">top</button>
    <button class="box-side bottom ">bottom</button>
</div>

<script src="../build/jquery-3.1.1.min.js"></script>
<script src="../build/three.js"></script>
<script src="../build/Math.js"></script>

<script src="js/renderers/Projector.js"></script>
<script src="js/renderers/CanvasRenderer.js"></script>

<script src="js/controls/OrbitControls.js"></script>

<script src="js/libs/stats.min.js"></script>

<script>
    var texture_placeholder;
    var container, stats;
    container = document.getElementById('container');

    var camera, scene, renderer;

    var cube;

    var containerWindow = {
        width: window.innerWidth,
        height: window.innerHeight
    };
    function refreshContainerWindowDimension() {
        containerWindow = {
            width: $(container).innerWidth(),
            height: $(container).innerHeight()
        };
    }
    refreshContainerWindowDimension();
    var windowHalfX = containerWindow.width / 2;
    var windowHalfY = containerWindow.height / 2;

    var box = {
        width: myMath.randFloat(1, 360),
        height: myMath.randFloat(1, 360),
        depth: myMath.randFloat(1, 360)
    };
    console.log(box);
    var boxMath = function () {
        var sum = box.width + box.height + box.depth;
        return {
            min: Math.min(box.width, box.height, box.depth),
            sum: sum,
            avg: sum / 3,
            max: Math.max(box.width, box.height, box.depth),
            hypotenuse: Math.sqrt(Math.pow(box.width, 2) + Math.pow(box.height, 2) + Math.pow(box.depth, 2))
        };
    }();
    console.log(boxMath);
    var cameraPosition = function () {
        return {
            x: 0,
            y: boxMath.hypotenuse,
            z: boxMath.hypotenuse
        }
    }();
    var cameraPosition0 = {
        x: 0,
        y: 0,
        z: 0
    };


    var perspectiveCamera = function () {
        var far = 500 + (boxMath.hypotenuse * 2.4);
        var fov = 60 + (boxMath.max / 12);

        return {
            fov: fov,//60~90;
            aspect: containerWindow.width / containerWindow.height,
            near: 0.1,
            far: far//500~2000;
        };
    }();
    console.log(perspectiveCamera);
    var axisHelper;

    var controls;
    var boxMaterials = [];
    var materialImages = [
        'Bridge2/negx.jpg',
        'Bridge2/negy.jpg',
        'Bridge2/negz.jpg',
        'Bridge2/posx.jpg',
        'Bridge2/posy.jpg',
        'Bridge2/posz.jpg',
        'MilkyWay/dark-s_nx.jpg',
        'MilkyWay/dark-s_ny.jpg',
        'MilkyWay/dark-s_nz.jpg',
        'MilkyWay/dark-s_px.jpg',
        'MilkyWay/dark-s_py.jpg',
        'MilkyWay/dark-s_pz.jpg',
        'Park2/negx.jpg',
        'Park2/negy.jpg',
        'Park2/negz.jpg',
        'Park2/posx.jpg',
        'Park2/posy.jpg',
        'Park2/posz.jpg',
        'Park3Med/nx.jpg',
        'Park3Med/ny.jpg',
        'Park3Med/nz.jpg',
        'Park3Med/px.jpg',
        'Park3Med/py.jpg',
        'Park3Med/pz.jpg',
        'pisa/nx.png',
        'pisa/ny.png',
        'pisa/nz.png',
        'pisa/px.png',
        'pisa/py.png',
        'pisa/pz.png',
        'pisaRGBM16/nx.png',
        'pisaRGBM16/ny.png',
        'pisaRGBM16/nz.png',
        'pisaRGBM16/px.png',
        'pisaRGBM16/py.png',
        'pisaRGBM16/pz.png',
        'skybox/nx.jpg',
        'skybox/ny.jpg',
        'skybox/nz.jpg',
        'skybox/px.jpg',
        'skybox/py.jpg',
        'skybox/pz.jpg',
        'sun_temple_stripe.jpg',
        'sun_temple_stripe_stereo.jpg',
        'SwedishRoyalCastle/nx.jpg',
        'SwedishRoyalCastle/ny.jpg',
        'SwedishRoyalCastle/nz.jpg',
        'SwedishRoyalCastle/px.jpg',
        'SwedishRoyalCastle/py.jpg',
        'SwedishRoyalCastle/pz.jpg'
    ];

    init();
    animate();

    function init() {
        camera = new THREE.PerspectiveCamera(
            perspectiveCamera.fov, perspectiveCamera.aspect, perspectiveCamera.near, perspectiveCamera.far
        );

//        camera.position.x = boxMath.hypotenuse/4;
//        camera.position.y = boxMath.hypotenuse/2;
//        camera.position.z = boxMath.hypotenuse;
        camera.position.x = 0;
        camera.position.y = 0;
        camera.position.z = boxMath.hypotenuse;


        scene = new THREE.Scene();

        axisHelper = new THREE.AxisHelper(50);
        scene.add(axisHelper);
        camera.lookAt(axisHelper.position);

        // Cube

        var geometry = new THREE.BoxGeometry(box.width, box.height, box.depth);

        for (var i = 0; i < geometry.faces.length; i += 2) {

            var hex = Math.random() * 0xffffff;
            geometry.faces[i].color.setHex(hex);
            geometry.faces[i + 1].color.setHex(hex);

        }

        for (var i = 0; i < 6; i++) {
            boxMaterials.push(loadTexture('textures/cube/' + materialImages[Math.floor(Math.random() * materialImages.length)]));
        }


//        var material = new THREE.MeshBasicMaterial( { vertexColors: THREE.FaceColors, overdraw: 0.5 } );
        var material = new THREE.MultiMaterial(boxMaterials);


        cube = new THREE.Mesh(geometry, material);
        //cube.position.y = cameraPosition.y;
        //cube.castShadow = true;

        scene.add(cube);


        renderer = new THREE.CanvasRenderer();
        renderer.setClearColor(parseInt('0x' + ((1 << 24) * Math.random() | 0).toString(16)));
        renderer.setPixelRatio(window.devicePixelRatio);
        renderer.setSize(containerWindow.width, containerWindow.height);

        container.appendChild(renderer.domElement);


        // Add OrbitControls so that we can pan around with the mouse.
        controls = new THREE.OrbitControls(camera, renderer.domElement);


        stats = new Stats();
        container.appendChild(stats.dom);

        cameraPosition0 = {
            x: camera.position.x,
            y: camera.position.y,
            z: camera.position.z
        };
        window.addEventListener('resize', onWindowResize, false);

    }

    function onWindowResize() {
        refreshContainerWindowDimension();

        windowHalfX = containerWindow.width / 2;
        windowHalfY = containerWindow.height / 2;

        camera.aspect = containerWindow.width / containerWindow.height;
        camera.updateProjectionMatrix();

        renderer.setSize(containerWindow.width, containerWindow.height);

    }

    function animate() {

        requestAnimationFrame(animate);

        stats.begin();//X
        render();
        controls.update();
        stats.end();//X

    }

    function render() {
        renderer.render(scene, camera);
    }

    function loadTexture(path) {

        var texture = new THREE.Texture(texture_placeholder);
        var material = new THREE.MeshBasicMaterial({map: texture, overdraw: 0.5});

        var image = new Image();
        image.onload = function () {

            texture.image = this;
            texture.needsUpdate = true;

        };
        image.src = path;

        return material;

    }

    var active = "front";

    $(function () {
        $('.box-side').click(function () {

            if ($(this).hasClass("front")) {
                active = "front";
                camera.position.set(+(cameraPosition0.x), +(cameraPosition0.y), +(cameraPosition0.z));//
                cube.geometry.faces[0].textureCubeIndex = 0;
            } else if ($(this).hasClass("left")) {
                active = "left";
                camera.position.set(-(cameraPosition0.z), +(cameraPosition0.y), +(cameraPosition0.x));//
            } else if ($(this).hasClass("back")) {
                active = "back";
                camera.position.set(-(cameraPosition0.x), +(cameraPosition0.y), -(cameraPosition0.z));//
            } else if ($(this).hasClass("right")) {
                active = "right";
                camera.position.set(+(cameraPosition0.z), +(cameraPosition0.y), -(cameraPosition0.x));//
            } else if ($(this).hasClass("top")) {
                active = "top";
                camera.position.set(+(cameraPosition0.x), +(cameraPosition0.z), +(cameraPosition0.y));
            } else if ($(this).hasClass("bottom")) {
                active = "bottom";
                camera.position.set(+(cameraPosition0.x), -(cameraPosition0.z), +(cameraPosition0.y));
            }
            controls.update();
            console.log(active);

        });
    });

</script>

</body>
</html>
