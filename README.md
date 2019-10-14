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

We dev some SQL like (actually not really like) language to get interesting selections from catalogs data.

If we want to select all objects of "person"s type we write just: 
person

Optional double qoutes: 
"person"

If we now want to select also their mothers we write: 
person mother

If we wanna select only person Bob and his mother we write:
person='Bob' mother

If we wanna to select only person with mother Alice we write: 
person: mother='Alice'
If we wanna select only mother of person Bob we write: 
person='Bob'.mother

So we added dot character (".") after Bob.
If we wanna select person with mother Alice but do not want to select mother herself, we add dot after her: 
person: mother='Alice'.

Or:
person: mother.='Alice'

We can use breaks. Lets find all grandsons of Alice: 
person: (mother='Alice') son

Last "son" word related to "person" word (not to mother) because or breaks.
This request we can rewrite this way:
person='Alice' son son

If we don't want to select Alice and her sons of first gen we just add dots:
person='Alice'.son.son

We can use OR (|), AND (^), CONCAT (U). For example select Alice sons and daugthers:
person='Alice' (son U daughter)

Or select childs of simultaniusly Alice and Bob (if they had fun once occasionly):
person: (mother='Alice' ^ father='Bob')

We can define functions with double "=" character. For example lets define grandchilds function:
grandchild==(child.child)

And then use it:
person='Alice'.grandchild

And we can use recursion to select all Alice generations of childs:
person='Alice'.f==(child U this.f)

We can use comma to select several properties of some object:
person="Bob" (mother, father, brother, syster)

This is not the same thing with CONCAT (U), because if we write next to the breakets some code, it will be related only to first item in brackets (mother in this example).
