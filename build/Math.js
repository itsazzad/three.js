function MyMath() {

    // Random integer from <low, high> interval
    this.randInt = function ( low, high ) {

        return low + Math.floor( Math.random() * ( high - low + 1 ) );

    };

    // Random float from <low, high> interval
    this.randFloat = function ( low, high ) {

        return low + Math.random() * ( high - low );

    };

    // Random float from <-range/2, range/2> interval
    this.randFloatSpread = function ( range ) {

        return range * ( 0.5 - Math.random() );

    };

}

var myMath = new MyMath();

