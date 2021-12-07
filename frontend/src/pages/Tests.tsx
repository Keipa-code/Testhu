import { Button, Col, Container, FormControl, InputGroup, Row } from 'react-bootstrap';
import TestsList from '../components/Test/TestsList';

const Tests = () => {
  return (
    <Container>
      <Row>
        <Col className="col-sm-8">
          <h2>Поиск тестов</h2>
          <InputGroup className="mb-3">
            <FormControl
              placeholder="Название или тема"
              aria-label="Название или тема"
              aria-describedby="basic-addon2"
            />
            <Button variant="outline-secondary" id="button-addon2">
              Найти
            </Button>
          </InputGroup>
          <TestsList />
        </Col>
        <Col className="col-sm-4">
          <p className="ms-5 mt-5">
            Чтобы найти нужный вам тест, введите тему, имя пользователя или ключевые слова в поле поиска. Тесты
            выводятся по убыванию популярности.
          </p>
          <Button className="ms-5 mt-5">Создать свой тест</Button>
          <br />
          <div className="ms-5 mt-5">
            <a href={'#'}>Зарегистрироваться</a>
          </div>
        </Col>
      </Row>
    </Container>
  );
};

export default Tests;
