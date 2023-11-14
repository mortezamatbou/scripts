
class Person {

    constructor(firstName, lastName, age) {
        this.firstName = firstName;
        this.lastName = lastName;
        this.age = age;
    }

    getInfo() {
        return { firstName: this.firstName, lastName: this.lastName, age: this.age };
    }

    getFirstName() {
        return this.firstName;
    }

    getLastName() {
        return this.lastName;
    }

    getAge() {
        return this.age;
    }

    getPosition() { }

}

class Student extends Person {

    constructor(firstName, lastName, age, field) {
        super(firstName, lastName, age);
        this.field = field;
    }

    getPosition() {
        return this.field;
    }

}

class Employee extends Person {

    constructor(firstName, lastName, age, post) {
        super(firstName, lastName, age);
        this.post = post;
    }

    getPosition() {
        return this.post;
    }

}

let morteza = new Student("Morteza", "Matbou", 30, "IT");
let mori = new Employee("Morteza", "Matbou", 30, "FOUNDER");
let hossein = new Employee("Hossein", "Allahmoradi", 24, "CEO");

console.log(morteza.getInfo(), "|", hossein.getInfo(), "|", mori.getInfo());
console.log(morteza.getPosition(), "|", hossein.getPosition(), "|", mori.getPosition());

