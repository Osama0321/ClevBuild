L.Control.cascadeButtons = L.Control.extend({
    options: {
        position: 'bottomright',
        direction: 'horizontal',
        className: ''
    },

    initialize: function(buttons, options){
        L.Util.setOptions(this, options);
        this._buttons = buttons;
    },

    onAdd: function (map){
        const className = (this.options.className) ? this.options.className : 'leaflet-control-cascadeButtons';
        const directionClass = this.buildDirection(this.options.direction);
        const toolBar = L.DomUtil.create('div', `${className} ${directionClass}`);

        let activeMainButton = null; // To keep track of the active main button

        this._buttons.forEach((button)=>{
            const directionClass = this.buildDirection(this.getOposite(this.options.direction));
            const container = L.DomUtil.create('div', `${directionClass}`);
            toolBar.append(container);

            const mainButton = L.DomUtil.create('button', `${button.icon}`);
            mainButton.setAttribute("type", "button");
            mainButton.setAttribute("aria-expanded", "false");
            mainButton.setAttribute("title", button.title); // Add title attribute
            container.append(mainButton);

            if(button.items && button.items.length>0){
                button.items.forEach((item)=>{
                    const childButton = L.DomUtil.create('button',`${item.icon} hidden`);
                    childButton.setAttribute("type", "button");
                    childButton.setAttribute("aria-expanded", "false");
                    childButton.setAttribute("title", item.title); // Add title attribute to inner button
                    container.append(childButton);
                    childButton.addEventListener('click', () => item.command());
                })

                mainButton.addEventListener('click', function(){
                    // Close any open inner buttons
                    toolBar.childNodes.forEach(container => {
                        if(container !== this.parentNode && container.childNodes[0] === activeMainButton) {
                            container.childNodes.forEach((child, index) => {
                                if(index!==0) child.classList.add('hidden');
                            });
                        }
                    });

                    container.childNodes.forEach((child, index) => {
                        if(index!==0) child.classList.toggle('hidden');
                    });

                    const isAriaExpanded = JSON.parse(mainButton.getAttribute("aria-expanded"));
                    mainButton.setAttribute('aria-expanded', !isAriaExpanded);
                    activeMainButton = isAriaExpanded ? null : mainButton;
                })
            } 
            else {
                mainButton.addEventListener('click', function(){
                    button.command();
                })
            }
        })
        L.DomEvent.disableClickPropagation(toolBar);
        return toolBar;
    },

    buildDirection: function(direction){
        if(direction === "vertical"){
            if((this.options.position).includes('left')){
                if(this.options.position.includes('bottom')) direction = direction + ' col-reverse'
            }
            if((this.options.position).includes('right')){
                if(this.options.position.includes('bottom')) direction = direction + ' col-reverse'
                direction = direction + ' right';
            }
        }
        else if(direction === "horizontal"){
            if((this.options.position).includes('top')){
                if(this.options.position.includes('right')) direction = direction + ' row-reverse';
            }
            if((this.options.position).includes('bottom')){
                if(this.options.position.includes('right')) direction = direction + ' row-reverse';
                direction = direction + ' bottom'
            }
        }
        return direction
    },

    getOposite: function(direction){
        return (direction === "vertical") ? "horizontal" : "vertical"
    }
})

L.cascadeButtons = function(buttons, options){
    return new L.Control.cascadeButtons(buttons, options);  
}
