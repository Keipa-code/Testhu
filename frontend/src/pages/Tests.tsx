import { Button, Col, Container, FormControl, InputGroup, Row } from 'react-bootstrap';
import TestsList from '../components/Test/TestsList';

const Tests = () => {
  const itemCount = 5;

  const view = {
    'hydra:first': 'https://localhost:8081/tests?page=2',
    'hydra:last': 'https://localhost:8081/tests?page=5',
    'hydra:next': 'https://localhost:8081/tests?page=3',
    'hydra:previous': 'https://localhost:8081/tests?page=1',
  };

  const tests = [
    { id: 1, testName: 'Тест по арифметике', passed: 100, done: 200, tags: ['Fizika', 'Math', 'Chemistry'] },
    { id: 2, testName: 'Тест по физике', passed: 50, done: 100, tags: ['Fizika', 'Math', 'Chemistry'] },
    { id: 3, testName: 'Тест по химия', passed: 20, done: 140, tags: ['Fizika', 'Math', 'Chemistry'] },
    { id: 4, testName: 'Тест по астро', passed: 220, done: 1200, tags: ['Fizika', 'Math', 'Chemistry'] },
    { id: 5, testName: 'Тест по поэзия', passed: 440, done: 1040, tags: ['Fizika', 'Math', 'Chemistry'] },
  ];

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
          <TestsList tests={tests} tableInfo={''} pagination={view} itemCount={itemCount} />
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
