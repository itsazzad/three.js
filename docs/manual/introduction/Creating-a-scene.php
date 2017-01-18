<html>
<head>
    <title>My first three.js app</title>
    <style>
        body { margin: 0; }
        canvas { width: 100%; height: 100% }
    </style>
</head>
<body>
<script src="../../../build/three.js"></script>

<script src="../../../examples/js/renderers/Projector.js"></script>
<script src="../../../examples/js/renderers/CanvasRenderer.js"></script>

<script>
    var scene = new THREE.Scene();
    var camera = new THREE.PerspectiveCamera( 75, window.innerWidth/window.innerHeight, 0.1, 1000 );

    var renderer = new THREE.CanvasRenderer();
    renderer.setClearColor(parseInt('0x' + ((1 << 24) * Math.random() | 0).toString(16)));
    renderer.setSize( window.innerWidth, window.innerHeight );
    document.body.appendChild( renderer.domElement );

    var box = {
        width: Math.random(),
        height: Math.random(),
        depth: Math.random()
    };
    var geometry = new THREE.BoxGeometry( box.width, box.height, box.depth );
    var material = new THREE.MeshBasicMaterial( {color: parseInt('0x' + ((1 << 24) * Math.random() | 0).toString(16))} );
    var cube = new THREE.Mesh( geometry, material );
    scene.add( cube );

    function cameraPosition() {
        return {
            x: 0,
            y: 0,
            z: Math.sqrt((Math.pow(box.width, 2) + Math.pow(box.height, 2) + Math.pow(box.depth, 2)))
        }
    }
    camera.position.z = cameraPosition().z;

    var render = function () {
        requestAnimationFrame( render );

        cube.rotation.x += Math.random() / 10;
        cube.rotation.y += Math.random() / 10;
        cube.rotation.z += Math.random() / 10;

        renderer.render(scene, camera);
    };

    render();
</script>
</body>
</html>