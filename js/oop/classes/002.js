
class Point {

    constructor(x, y) {
        this.x = x;
        this.y = y;
    }

}

class Line {

    /**
     * 
     * @param {Point} p1 
     * @param {Point} p2 
     */
    constructor(p1, p2) {
        this.p1 = p1;
        this.p2 = p2;
    }

    getP1() {
        return this.p1;
    }

    getP2() {
        return this.p2;
    }

    getX1() {
        return this.p1.x;
    }

    getY1() {
        return this.p1.y;
    }

    getX2() {
        return this.p2.x;
    }

    getY2() {
        return this.p2.y;
    }

    getXY() {
        return { x1: this.p1.x, y1: this.p1.y, x2: this.p2.x, y2: this.p2.y };
    }

    move(p1, p2) {
        this.p1 = p1;
        this.p2 = p2;
    }

    moveP1(p1) {
        this.p1 = p1;
    }

    moveP2(p2) {
        this.p2 = p2;
    }

}


class Rectangle {

    /**
     * 
     * @param {Point} p1 
     * @param {Point} p2 
     */
    constructor(p1, p2) {
        this.p1 = p1;
        this.p2 = p2;
    }

    getP1() {
        return this.p1;
    }

    getP2() {
        return this.p2;
    }

    getX1() {
        return this.p1.x;
    }

    getY1() {
        return this.p1.y;
    }

    getX2() {
        return this.p2.x;
    }

    getY2() {
        return this.p2.y;
    }

    getXY() {
        return { x1: this.p1.x, y1: this.p1.y, x2: this.p2.x, y2: this.p2.y };
    }

    move(p1, p2) {
        this.p1 = p1;
        this.p2 = p2;
    }

    moveP1(p1) {
        this.p1 = p1;
    }

    moveP2(p2) {
        this.p2 = p2;
    }

}

let line = new Line(new Point(1, 1), new Point(10, 5));
console.log(line.getXY());

line.move(new Point(10, 10), new Point(20, 35))
console.log(line.getXY());

line.moveP1(new Point(1, 1));
console.log(line.getXY());

rect = new Rectangle(new Point(20, 20), new Point(40, 40));
console.log(rect.getXY());