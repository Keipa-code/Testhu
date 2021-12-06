import { FC } from 'react';
import 'bootstrap/dist/css/bootstrap.min.css';
import { Col, Container, Row } from 'react-bootstrap';
import { BrowserRouter, NavLink, Route } from 'react-router-dom';
import Home from './pages/Home';
import Tests from './pages/Tests';
import NewTest from './pages/NewTest/NewTest';

const App: FC = () => {
  return (
    <BrowserRouter>
      <div>
        <Container>
          <Row>
            <Col sm={10}>
              <h1>TestHub</h1>
              <div>
                <NavLink to="/">Home</NavLink>
                <NavLink to="/tests">Tests</NavLink>
              </div>
            </Col>
            <Col sm={2}>
              <a style={{ alignItems: 'end' }} href="#">
                Вход
              </a>
            </Col>
          </Row>
        </Container>
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
