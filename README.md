# Mongorm
A MongoDB Implementation for Eloquent.
This package should allow you to work with Laravel's Eloquent ORM just like you always do, yet with a MongoDB database being used.

[![Build Status](https://img.shields.io/travis/packedge/mongorm.svg?branch=master&style=flat-square)](https://travis-ci.org/packedge/mongorm)
[![Code Quality](https://img.shields.io/scrutinizer/g/packedge/mongorm.svg?branch=master&style=flat-square)](https://scrutinizer-ci.com/g/packedge/mongorm)
[![Test Coverage](https://img.shields.io/scrutinizer/coverage/g/packedge/mongorm.svg?branch=master&style=flat-square)](https://scrutinizer-ci.com/g/packedge/mongorm)


##Package Development
This package is still in development.

####Standard Features
- [x] Detect collection/table names
- [x] Dynamically access db attributes
- [x] Access attributes on the model like an array
- [ ] Set connections in the config
- [ ] Change connection e.g. Model::on('something')->find()
- [x] Accessors & mutator methods
- [x] Override primary key (default to '_id')
- [x] Return dates as Carbon instances
- [ ] Save as mongodates
- [x] Support casting data
- [x] Cast any mongo types to PHP types.
- [ ] Mass assignemnt protection
- [ ] Soft deletes
- [ ] Timestamps
- [ ] Query scopes
- [ ] Dynamic scopes
- [ ] Model events
- [ ] Model observers
- [ ] Convert to array, json
- [ ] Hiding attributes from array, json output
- [ ] Apending attributes to array, json

####Model Methods
- [x] Model::all()
- [ ] Model::find()
- [ ] Model::findOrFail()
- [ ] Model::where()
- [ ] Model::orWhere()
- [ ] Model::first()
- [ ] Model::firstOrFail()
- [ ] Saving
- [ ] Model::create()
- [ ] Model::firstOrCreate()
- [ ] Model::firstOrNew()
- [ ] deleting
- [ ] Model::destroy()
- [ ] touch timestamps
- [ ] with trashed (soft deletes)
- [ ] Model::restore()
- [ ] Model::forceDelete()

####Relationships
- [ ] one to one
- [ ] one to many
- [ ] many to many
- [ ] embeds one
- [ ] embeds many

####Advanced Relationships
- [ ] has many through
- [ ] polymorphic
- [ ] many to many polymorphic
- [ ] eager loading

####Extra Features
- [ ] Laravel validator support
- [ ] Auth driver
- [ ] Usable where "database" driver is use. (e.g. cache etc)
- [ ] Console overrides: e.g. make:model to make model work with our implementation
- [ ] Migration support (for building indexes)

##Maintainers

This package is maintained by:
- [Ashley Clarke](https://twitter.com/clarkeash)
- [Steve Axtmann](https://twitter.com/Fllambe)

##Contributions

As this package is currently in the very early stages, we will not accept pull requests with new features. If there is a feature we have marked as complete in the list above and its broken then feel free to complain at us in an issue or submit a fix via a pull request.

Once we reach a stable state we will of course handle pull requests in a much more welcoming fashion.

##License

This package is released under the MIT license. Please see the [License File](LICENSE) for more information.
