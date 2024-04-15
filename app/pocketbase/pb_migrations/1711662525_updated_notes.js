/// <reference path="../pb_data/types.d.ts" />
migrate((db) => {
  const dao = new Dao(db)
  const collection = dao.findCollectionByNameOrId("3e2nuhk2vohrjxn")

  collection.listRule = ""
  collection.createRule = ""

  return dao.saveCollection(collection)
}, (db) => {
  const dao = new Dao(db)
  const collection = dao.findCollectionByNameOrId("3e2nuhk2vohrjxn")

  collection.listRule = null
  collection.createRule = null

  return dao.saveCollection(collection)
})
