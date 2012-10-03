var Blop, Spine, Teacher, User, assert, sayName, user;

Spine = require("spine");

assert = require("assert");

/* sub([includeProperties , extendProperties ])
*/

User = Spine.Class.sub();

User = Spine.Class.sub({
  instanceFuction: function() {}
});

user = new User();

Teacher = User.sub();

/* extend(Module)
*/

User.extend({
  find: function() {
    return console.log("User.find()");
  }
});

User.find();

/* include(Module)
*/

User = Spine.Class.sub();

User.include({
  name: "User name"
});

/* proxy  : binds a function to a context
*/

sayName = function() {
  return console.log(this.name);
};

User.sayName = sayName;

User.proxy(sayName);

Blop = {
  sayName: sayName
};

console.log(Blop.sayName(), User.sayName());
