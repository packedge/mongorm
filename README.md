# Mongorm
A MongoDB Implementation for Eloquent.
This package should allow you to work with Laravel's Eloquent ORM just like you always do, yet working with a MongoDB database being used.

##Package Development
This package is still in development.

###Progress

####Standard Features
- [x] Detect collection/table names
- [x] dynamically access db attributes
- [x] access attributes on the model like an array
- [ ] set connections in the config
- [ ] change connection e.g. Model::on('something')->find()
- [x] accessors & mutator methods
- [ ] override primary key (default to '_id')
- [ ] return dates as Carbon instances, save as mongodates
- [ ] support casting data
- [ ] mass assignemnt protection
- [ ] soft deletes
- [ ] timestamps
- [ ] query scopes
- [ ] dynamic scopes
- [ ] model events
- [ ] model observers
- [ ] convert to array, json
- [ ] hiding attributes from array, json output
- [ ] apending attributes to array, json

####Model Methods
- [ ] Model::all()
- [ ] Model::find()
- [ ] Model::findOrFail()
- [ ] Model::where()
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



##License

This package is released under the MIT license. Please see the [License File](LICENSE) for more information.
