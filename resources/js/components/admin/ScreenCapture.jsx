import { Component } from 'react';
import { createRoot } from 'react-dom/client';
export default class ScreenCapture extends Component {
    constructor() {
        super();
        this.state = {
            image: ''
        };

        Echo.private(`screencaptures.bruh`)
            .listen('ScreencaptureReceive', this.handleImage.bind(this));
    }

    async handleImage(data) {
        this.setState({
            image: data.image
        });
    }

    async componentDidMount() {
    }

    render() {
        return <div><img src={this.state.image}/></div>;
    }
}

var banDialogs = document.getElementsByTagName('react-screen-capture');

for (var index in banDialogs) {
    const component = banDialogs[index];
    if(typeof component === 'object') {
        const props = Object.assign({}, component.dataset);
        const root = createRoot(component);
        root.render(<ScreenCapture {...props} />);
        // ReactDOM.render(<ScreenCapture {...props} />, component);
    }
}
