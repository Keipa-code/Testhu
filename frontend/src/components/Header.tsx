import { Col, Container, Row } from 'react-bootstrap';
import { Link, useHistory } from 'react-router-dom';
import { FC, useState } from 'react';
import { Button, Menu } from 'antd';

const Header: FC = () => {
  const router = useHistory();
  const [current, setCurrent] = useState(router.location.pathname);
  const handleClick = (e) => {
    setCurrent(e.key);
    router.push(e.key);
  };

  return (
    <div className="container-fluid mb-3 header-color">
      <Container>
        <Row className="pt-3 pb-3">
          <Col className="col-sm-10">
            <Menu onClick={handleClick} mode="horizontal" theme="dark" selectedKeys={[current]} className="font-13">
              <Menu.Item key="/">TestHub</Menu.Item>
              <Menu.Item key="/tests">Tests</Menu.Item>
              <Menu.Item key="/new">New tests</Menu.Item>
            </Menu>
          </Col>
          <Col className="d-flex flex-row-reverse align-items-center" sm={2}>
            <Button type="primary">
              <Link className="link" to="login">
                Войти
              </Link>
            </Button>
          </Col>
        </Row>
      </Container>
    </div>
  );
};

export default Header;
