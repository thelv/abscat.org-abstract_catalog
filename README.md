# abscat.org-abstract_catalog
Abscat.org (abstract catalog) website source code. This is an alternative and more human logic approach to data structuring and sql. It can be used for catalogization of your music library and you can listen your music right from website (youtube and vk services used for music search).

Hello. This project provide the idea of making universal and minimal structure for describing any catalog data. 
May be your music, may be library books or may be anything else.

The structure we found is very simple and it fits with any standart database data.
Structure contains from objects and connections between them. Any object has a type (type is not necessary as we show later) and content. 
Type and content is just a pieces of text. Any connection connects only two objects within them and has two relation names.
First is the name of relation from one object to another. And the second is in the opposite way.

For example we have two objects of type "person": Bob and Alice.
And we have connection bitween them this relation names "mother" (from Bob to Alice) and "son" (from Alice to Bob).
