/// <reference path="../pb_data/types.d.ts" />
migrate((db) => {
  const dao = new Dao(db)
  const collection = dao.findCollectionByNameOrId("47k9ybg7l6ug3qj")

  collection.listRule = null

  return dao.saveCollection(collection)
}, (db) => {
  const dao = new Dao(db)
  const collection = dao.findCollectionByNameOrId("47k9ybg7l6ug3qj")

  collection.listRule = "@request.data.publicDate >= @now"

  return dao.saveCollection(collection)
})
