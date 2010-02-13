
var formResponce = null;
var bChanged = false;
var comet;

var rebuildSVG = function(State)
{
	//var oForm = $("controller");//el.getParent('form');
	var oState = new Hash(State);
	var sQuery = oState.toQueryString();
	
	var eOutputframe = $('outputframe');
	eOutputframe.set('src', 'k.php?'+sQuery);
	eOutputframe.set('width', oState.width);
	eOutputframe.set('height', oState.height);
};	

var Comet = new Class({
  timestamp: 0,
  url: './backend.php',
  noerror: true,
	ajax:null,
	sender:null,
	
	connect: function()
  {
    //console.log('connect this', this);
    this.ajax.send('timestamp='+this.timestamp);
  },
  
  disconnect: function()
  {
  },
  
  initialize: function() {
		this.ajax = new Request.JSON({
			url: this.url,
      method: 'get',
      onSuccess: function(response) {
				//console.log('ajax sucess this',this,'response', response);
        
				if($defined(response.timestamp))
				{
					// handle the server response
					this.timestamp = response.timestamp;
					this.handleResponse(response);
					this.noerror = true;
				}
				else if ($defined(response.recieved))
				{
				  this.noerror = true;
				  //console.log("response.recieved =", response.recieved);
				}
				else
				{
				  //console.log("failing response =", response);
					this.noerror = false;
				}
      }.bind(this),
      
      onComplete: function(transport) {
        
        //console.log("onComplete transport =", transport);
        // send a new ajax request when this request is finished
        if (!this.noerror)
				{
					//console.log('error retrying');
          // if a connection problem occurs, try to reconnect each 5 seconds
          this.connect().delay(5000); 
        }
				else
        {
					this.connect();
        }
				this.noerror = false;
      }.bind(this)
		});

		this.sender = new Request({
			url: this.url,
			method: 'get'
		});
	},
	
  handleResponse: function(response)
  {
		formResponse = new Hash(JSON.decode(response.msg));
		//console.log('formResponse', formResponse);
		bChanged = true;
		

		var oForm = $("controller");//el.getParent('form');
		if(bControls)
		{
			bChanged = false;
			//"width":800,"height":800
			oForm.getElements('input,select,textarea').each(function(item){
				var sElementName = item.get('name');
				//console.log('item',item,'key',key);
				//this covers the width / height and other base var here
				
				xValue = formResponse.getFromPath(sElementName);
				//console.log("xValue =", xValue);
				if($defined(xValue) && $defined(sElementName))
				{
					if(formResponse[sElementName] == xValue)
					{
						//console.log(sElementName, 'unchanged');
					}
					else
					{
						bChanged = true;
						item.set('value', xValue);
						//console.log("xValue =", xValue);
						if ($defined(item.oSlider))
						{
						  item.oSlider.set(xValue);
						}
					}
				}
				
				
				//if it doesn't exist its a new bit so create it using the templating functions.
			});
		}
		if (bChanged && bViewer)
		{
			rebuildSVG(formResponse);
		}
    //oForm.getElements('input,select,textarea');
		    
    //$('content').innerHTML += '<div>' + response['msg'] + '</div>';
  },
	

	
  doRequest: function(request)
  {
		this.sender.send(request);
  }
});


	var oFormChangeTimer = null;
  var formChange = function(el)
  {
    var oForm = el.getParent('form');
        
    comet.doRequest('msg='+escape(JSON.encode(oForm.getFormValues()))+'');
    if(bViewer)
    {
      rebuildSVG(oForm.getFormValues());
    }
  }.create({delay:500});

			
			
Element.implement({
		getFormValues: function(){
			var aValues = this.getElements('input,select,textarea').get('value');
			var aNames = this.getElements('input,select,textarea').get('name');
			var oValues = new Hash();
			
			for (var i = 0; i <aNames.length; i++)
			{
				oValues.setFromPath(aNames[i], aValues[i]);
			}
			return oValues;
		},
		
  mooslider: function(options) {
    var input = this;
		if (input.get("tag") == "input")
    {
			options = $extend({}, options);
      var iMin = input.getAttribute('min').toInt();
      var iMax = input.getAttribute('max').toInt();
      input.options = options;
      
      input.slider_control = new Element('div');
      input.slider_control.addClass('slider_control');
      
      input.knob = new Element('div');
      input.knob.addClass('knob');
      input.slider_control.adopt(input.knob);
      
      input.slider_control.inject(input, 'after');
      
      input.set('type', 'hidden');
      
      var oSlider = new Slider(input.slider_control, input.knob, {
        range: [iMin, iMax],	// Minimum value is 8
        onChange: function(value)
        {
          // Everytime the value changes, we change the font of an element
          input.set('value', value);
          input.knob.set('html', value);
          if(oFormChangeTimer)
          {
            $clear(oFormChangeTimer);
          }
          oFormChangeTimer = formChange(input);
        }
      }).set(input.get('value').toInt());
			input.oSlider = oSlider;
    }
    return input;
  }
});


Hash.implement({
    setFromPath: function(path, value) {
        var source = this;
        var prop = '';

        path.replace(/\[([^\]]+)\]|\.([^.[]+)|[^[.]+/g, function(match) {
            if (!source) return;
            prop = arguments[2] || arguments[1] || arguments[0];

            if (!(prop in source)) source[prop] = {};
            lastSource = source;
            source = source[prop];
            return match;
        });

        lastSource[prop] = value;
        return this;
    }
});



// AUTOLOAD CODE BLOCK (MAY BE CHANGED OR REMOVED)
window.addEvent("domready", function() {
	
	comet = new Comet();
	comet.connect();
	if(bControls)
	{
		$$("input").filter(function(input) { return input.hasClass("slider"); }).mooslider({});
		$('controller').getElements('input').addEvent('change', function(event){oFormChangeTimer = formChange(event.target)});
  }
});