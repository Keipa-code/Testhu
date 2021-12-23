import { useParams, Link } from 'react-router-dom';
import { Button, Col, Form, Input, Row } from 'antd';

const Publish = () => {
  const { id, token } = useParams<{ id: string; token: string }>();
  const testUrl = `/test/${id}`;
  const resultCheckUrl = `/result/${id}/${token}`;

  return (
    <div className="container">
      <h2>Публикация теста</h2>
      <p>
        Тест успешно создан. Вы можете ввести свой e-mail, что бы получать на него уведомления о прохождении тестов, а
        также придумать себе пароль для входа на сайт.
      </p>
      <Row>
        <Col className="ant-col-10">
          <Form>
            <Form.Item label="E-mail">
              <Input />
            </Form.Item>
          </Form>
        </Col>
        <Col className="ant-col-6 ms-3">
          <Form>
            <Form.Item>
              <Button type="primary">Сохранить</Button>
            </Form.Item>
          </Form>
        </Col>
      </Row>
      <p>
        По этой <Link to={testUrl}>ссылке</Link> вы можете пройти тест
      </p>
      <p>
        Ссылка для просмотра ресультатов тестов <Link to={resultCheckUrl}>посмотреть результаты</Link>
      </p>
    </div>
  );
};

export default Publish;
