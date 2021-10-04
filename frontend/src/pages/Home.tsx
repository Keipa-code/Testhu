import React from 'react';
import {Button, Col, Container, Row} from "react-bootstrap";
import TestsList from "../components/TestsList";

const MyComponent = () => {

    const info = 'TestHub — это сервис, который позволяет вам легко создавать тесты для проверки знаний и просматривать результаты в удобном интерфейсе. Для создания и прохождения теста не требуется регистрация, но мы советуем это сделать, так как в этом случае вы легко сможете управлять своими тестами.'

    const tests = [
        {id: 1, testName: 'Тест по арифметике',  passed: 100, done: 200, tags: [{"id":0,"tagName":"Fizika"},{"id":1,"tagName":"Math"},{"id":2,"tagName":"Chemistry"}]},
        {id: 2, testName: 'Тест по физике',  passed: 50, done: 100, tags: [{"id":0,"tagName":"Fizika"},{"id":1,"tagName":"Math"},{"id":2,"tagName":"Chemistry"}]},
        {id: 3, testName: 'Тест по химия',  passed: 20, done: 140, tags: [{"id":0,"tagName":"Fizika"},{"id":1,"tagName":"Math"},{"id":2,"tagName":"Chemistry"}]},
        {id: 4, testName: 'Тест по астро',  passed: 220, done: 1200, tags: [{"id":0,"tagName":"Fizika"},{"id":1,"tagName":"Math"},{"id":2,"tagName":"Chemistry"}]},
        {id: 5, testName: 'Тест по поэзия',  passed: 440, done: 1040, tags: [{"id":0,"tagName":"Fizika"},{"id":1,"tagName":"Math"},{"id":2,"tagName":"Chemistry"}]}
    ]
    return (
        <Container>

            <Row>
                <Col sm={8}>
                    <h2>Попробовать свои силы</h2>
                    <TestsList tests={tests} tableInfo={'none'}/>
                </Col>
                <Col sm={4}>
                    <h2>О сайте</h2>
                    <p>{info}</p>
                    <br/>
                    <br/>
                    <p>Присоединяйтесь к сообществу TestHub</p>
                    <Button variant="outline-primary">Создать тест</Button>
                    <br/>
                    <a href="#">Зарегистрироваться</a>
                    <br/>
                    <a href="#">Подробнее о сайте TestHub</a>
                </Col>
            </Row>
        </Container>
    );
};

export default MyComponent;
