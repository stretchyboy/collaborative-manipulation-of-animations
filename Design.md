# Design #

DESIGN

The control forms are in jsontemplate and the ids are done on an item id and then stuff to id the purpose.
there is a bit of javscript that is given the form values and transforms them in to the template vars needed (needed for manadala and complex things a default opne that passes through)


the emlement templates are  jsontemplate so that the current shared state can be served out in php.

the template has the items elements with ids so that the state can tweak things dirrectly if this is allowed (oh crap that the hard bit)

when the shared state is recived it is quickly checked to see if any elemnts have changed or been added. then it rebuilds or tweaks

a deleted one still exts but its state is used to {deleted:true}

must do an x,y drag controller.

implement
> declariteve mandala
> movable video mandala
> video effects
> movable video clip paths
> (mp3 hookup ?)