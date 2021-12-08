import { Col, Container, Nav, Row } from 'react-bootstrap';
import { Link, NavLink } from 'react-router-dom';
import { FC } from 'react';

const Header: FC = () => {
  return (
    <div className="container-fluid mb-3 header-color">
      <Container>
        <Row className="pt-3 pb-3">
          <Col sm={10} className="d-flex align-items-center">
            <Nav>
              <Nav.Item className="ms-3">
                <NavLink className="nav-link-logo" to="/">
                  <h1 className="text-white text-decoration-none">TestHub</h1>
                </NavLink>
              </Nav.Item>
              <Nav.Item className="ms-3">
                <NavLink className="nav-link" to="/tests">
                  Tests
                </NavLink>
              </Nav.Item>
              <Nav.Item className="ms-3">
                <NavLink className="nav-link" to="/new">
                  New tests
                </NavLink>
              </Nav.Item>
            </Nav>
          </Col>
          <Col className="d-flex flex-row-reverse align-items-center" sm={2}>
            <button className="btn btn-primary">
              <Link className="link" to="login">
                Войти
              </Link>
            </button>
          </Col>
        </Row>
      </Container>
    </div>
  );
};

export default Header;
