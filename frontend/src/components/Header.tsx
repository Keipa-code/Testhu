import { Link, useHistory } from 'react-router-dom';
import { FC, useState } from 'react';
import { Button, Menu, Col, Row } from 'antd';

const Header: FC = () => {
  const router = useHistory();
  const [current, setCurrent] = useState(router.location.pathname);
  const handleClick = (e) => {
    setCurrent(e.key);
    router.push(e.key);
  };

  return (
    <div className="mb-3 header-color">
      <div className="container">
        <Row className="pt-3 pb-3">
          <Col className="ant-col-sm-20">
            <Menu onClick={handleClick} mode="horizontal" theme="dark" selectedKeys={[current]} className="font-13">
              <Menu.Item key="/">TestHub</Menu.Item>
              <Menu.Item key="/tests">Tests</Menu.Item>
              <Menu.Item key="/new">New tests</Menu.Item>
            </Menu>
          </Col>
          <Col className="ant-col-offset-2">
            <Button type="primary">
              <Link className="link" to="login">
                Войти
              </Link>
            </Button>
          </Col>
        </Row>
      </div>
    </div>
  );
};

export default Header;
