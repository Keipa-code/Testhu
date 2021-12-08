import { Button, Col, Container, Row } from 'react-bootstrap';
import TestsList from '../components/Test/TestsList';

const Home = () => {
  const info =
    'TestHub — это сервис, который позволяет вам легко создавать тесты для проверки знаний и просматривать результаты в удобном интерфейсе. Для создания и прохождения теста не требуется регистрация, но мы советуем это сделать, так как в этом случае вы легко сможете управлять своими тестами.';

  return (
    <Container>
      <Row>
        <Col sm={8}>
          <h2>Попробовать свои силы</h2>
          <TestsList home={true} />
        </Col>
        <Col sm={4}>
          <h2>О сайте</h2>
          <p>{info}</p>
          <br />
          <br />
          <p>Присоединяйтесь к сообществу TestHub</p>
          <Button variant="outline-primary">Создать тест</Button>
          <br />
          <a href="#">Зарегистрироваться</a>
          <br />
          <a href="#">Подробнее о сайте TestHub</a>
        </Col>
      </Row>
    </Container>
  );
};

export default Home;
