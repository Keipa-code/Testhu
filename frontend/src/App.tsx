import { FC } from 'react';
import 'bootstrap/dist/css/bootstrap.min.css';
import { BrowserRouter, Route } from 'react-router-dom';
import Home from './pages/Home';
import Tests from './pages/Tests';
import NewTest from './pages/NewTest/NewTest';
import Header from './components/Header';

const App: FC = () => {
  return (
    <BrowserRouter>
      <div>
        <Header />
        <Route path={'/'} exact>
          <Home />
        </Route>
        <Route path={'/tests'} exact>
          <Tests />
        </Route>
        <Route path={'/new'} exact>
          <NewTest />
        </Route>
      </div>
    </BrowserRouter>
  );
};

export default App;
