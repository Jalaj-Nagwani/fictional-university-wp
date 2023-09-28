wp.blocks.registerBlockType('ourplugin/are-you-paying-attention', {
    title: "Are You Paying Attention",
    icon: "smiley",  // Dashicon
    category: "common",
    attributes: {
        skyColor: { type: "string" },
        grassColor: { type: "string" }
    },
    edit: function (props) {

        function updateSkyColor(event) {
            props.setAttributes({ skyColor: event.target.value })
        }

        function updateGrassColor(event) {
            props.setAttributes({ grassColor: event.target.value })
        }

        return (
            <div>
                <input type="text" placeholder="Sky Color" onChange={updateSkyColor} value={props.attributes.skyColor} />
                <input type="text" placeholder="Grass Color" onChange={updateGrassColor} value={props.attributes.grassColor} />
            </div>
        )
        // return wp.element.createElement("h3", null, "Hello World from Edit");  // Creating an HTML element from within JS using WP global scope
    },  // Responsible for what the Admin see in the Editor Screen
    save: function (props) {

        return null;

        // return wp.element.createElement("h3", null, "Hello World from Save");
    },  // Responsible for what the User see in the Frontend

    deprecated: [{
        attributes: {
            skyColor: { type: "string" },
            grassColor: { type: "string" }
        },
        save: function (props) {

            return (
                <h3>Today the grass is {props.attributes.grassColor} and the sky is {props.attributes.skyColor} .</h3>
            )
        },

    }]
});  // wp is added in the browser's global scope