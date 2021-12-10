import ReactDOM from 'react-dom';
import './index.css';
import 'antd/dist/antd.css';
import App from './App';
import { RootStateProvider } from './RootStateContext';

ReactDOM.render(
  <RootStateProvider>
    <App />
  </RootStateProvider>,
  document.getElementById('root')
);
