# abscat.org-abstract_catalog
Abscat.org (abstract catalog) website source code. This is an alternative and more human logic approach to data structuring and sql. It can be used for catalogization of your music library and you can listen your music right from website (youtube and vk services used for music search).

Hello. This project provides the idea of making universal and simplest structure for describing any catalog data. 
May be your music, may be library books or may be anything else.

The structure we found is very simple and it fits with any standart database data.
Structure contains from objects and connections between them. Any object has a type (type is not necessary as we show later) and content. 
Type and content is just a pieces of text. Any connection connects only two objects with each other and has two relation names.
First is the name of relation from one object to another. And the second is in the opposite way.

For example we have two objects of type "person": Bob and Alice.
And we have connection between them with relation names "mother" (from Bob to Alice) and "son" (from Alice to Bob).

We developed some SQL like (actually not really like) language to get interesting selections from catalogs data.

If we want to select all objects of "person"s type we write just: 
person

Optional double qoutes: 
"person"

If we now want to select also their mothers we write: 
person mother

If we wanna select only person Bob and his mother we write:
person='Bob' mother

If we wanna  select only person with mother Alice we use double dot (":") character:  
person: mother='Alice'

If we wanna select only mother of person Bob and not select Bob himself we write: 
person='Bob'.mother

So we added dot character (".") after Bob.
If we wanna select person with mother Alice but do not want to select mother herself, we add dot after her: 
person: mother='Alice'.

Or:
person: mother.='Alice'

We can use breakets. Lets find all grandsons of Alice: 
person: (mother='Alice') son

Last "son" word related to "person" word (not to mother) because of breakets.
This request we can rewrite this way:
person='Alice' son son

If we don't want to select Alice herself and her sons of first generation we just add dots:
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
This code will give us only grandmother of Bob from his mother side:
person="Bob" (mother, father, brother, syster) mother

P.S. Little trick to remove types from all the logic. We can define root object. And say that all objects with type "person" connect with root object through connection "person". With will have no influence on a way we write requests. So there is no difference either define types or define root object and connect objects with it through  corresponding connections.

And yet. Another feature in our language is that you can add dot before any type and parser will count the dot as root object.
For example: person (mother, .father)

.father will be considered as all objects with type "father", not the father of person. It will be similar with cross join in sql.

This "." synthax could be helpful with another construction we didn't introduce yet. Symbol "e" means belongs. 
This request: 
person: mother e .person.wife 

-- will find any person who married on his mother. It takes only objects from left side of "e" symbol, which belongs to object set from the right side of "e" sumbol.

We need to note that ".person" in this case will have not all persons for each person, who mother we need to check. If we want to take persons undependently from first person selection, we should use figure brackets {} and optionally number inside it. If no numbers provided means that this set of objects will be undependent from any others with same name. Same numbers mean same sets. No figure brackets equels to number 0: {0} -- BUT THIS FEATURE NOT WORKING RIGHT NOW
