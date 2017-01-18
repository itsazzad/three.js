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
        width: myMath.randFloat(1, 100),
        height: myMath.randFloat(1, 100),
        depth: myMath.randFloat(1, 100)
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
        var far;
        far = (boxMath.hypotenuse * 3);
        if(far>=500){
            if(far>2000){
                far = 2000;
            }
        }else{
            far = 500;
        }
        //far = 1000;

        var fov;
        fov = boxMath.avg;
        if(fov>=60){
            if(fov>90){
                fov = 90;
            }
        }else{
            fov = 60;
        }

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
    var textureCube;

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

        var material = new THREE.MeshBasicMaterial( { vertexColors: THREE.FaceColors, overdraw: 0.5 } );


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

    var active = "front";

    $(function() {
        $('.box-side').click(function(){

            if($( this ).hasClass( "front" )){
                active = "front";
                camera.position.set(+(cameraPosition0.x), +(cameraPosition0.y), +(cameraPosition0.z));//
            }else if($( this ).hasClass( "left" )){
                active = "left";
                camera.position.set(-(cameraPosition0.z), +(cameraPosition0.y), +(cameraPosition0.x));//
            }else if($( this ).hasClass( "back" )){
                active = "back";
                camera.position.set(-(cameraPosition0.x), +(cameraPosition0.y), -(cameraPosition0.z));//
            }else if($( this ).hasClass( "right" )){
                active = "right";
                camera.position.set(+(cameraPosition0.z), +(cameraPosition0.y), -(cameraPosition0.x));//
            }else if($( this ).hasClass( "top" )){
                active = "top";
                camera.position.set(+(cameraPosition0.x), +(cameraPosition0.z), +(cameraPosition0.y));
            }else if($( this ).hasClass( "bottom" )){
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
