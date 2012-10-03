Spine = require("spine")
assert = require("assert")
### sub([includeProperties , extendProperties ]) ###

User = Spine.Class.sub() # declaring a new class

User = Spine.Class.sub({ # extending classes properties
  instanceFuction:()->
    # code
  })

user = new User() # classes are constructor functions

Teacher = User.sub() # sub classing

### extend(Module) ###

User.extend({
  find:()->
    console.log "User.find()"
  })

User.find()

### include(Module)  ###

User =Spine.Class.sub()
User.include({
  name:"User name"
})

### proxy  : binds a function to a context ###

sayName = ()->
  console.log @name

User.sayName = sayName

User.proxy(sayName)

Blop =
  sayName:sayName
  

console.log Blop.sayName(),User.sayName()

