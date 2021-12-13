import { Button, Col, Divider, Row } from 'antd';
import TestsList from '../components/Test/TestsList';

const Home = () => {
  const info =
    'TestHub — это сервис, который позволяет вам легко создавать тесты для проверки знаний и просматривать результаты в удобном интерфейсе. Для создания и прохождения теста не требуется регистрация, но мы советуем это сделать, так как в этом случае вы легко сможете управлять своими тестами.';

  return (
    <div className="container">
      <Row gutter={16}>
        <Col className="gutter-row" flex="auto">
          <h2>Попробовать свои силы</h2>
          <Divider />
          <TestsList home={true} />
        </Col>
        <Col className="gutter-row" span={6} flex="auto">
          <h2>О сайте</h2>
          <p>{info}</p>
          <br />
          <br />
          <p>Присоединяйтесь к сообществу TestHub</p>
          <Button type="primary">Создать тест</Button>
          <br />
          <a href="#">Зарегистрироваться</a>
          <br />
          <a href="#">Подробнее о сайте TestHub</a>
        </Col>
      </Row>
    </div>
  );
};

export default Home;
