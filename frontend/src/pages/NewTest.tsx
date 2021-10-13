import React from 'react';
import {Button, Col, Container, Form, FormCheck, FormControl, InputGroup, Row} from "react-bootstrap";

const NewTest = () => {
    return (
        <Container>

            <Row>
                <Col className="col-sm-8">
                    <h2>Создать новый тест</h2>
                        <FormControl
                            className="mt-3"
                            placeholder="Название"
                            aria-label="Название"
                            aria-describedby="basic-addon2"
                        />
                        <FormControl
                            className="mt-3"
                            placeholder="Тэги"
                            aria-label="Тэги"
                            aria-describedby="basic-addon2"
                        />
                        <Button className="mt-3">Добавить предисловие</Button>
                    <Form>
                        <Form.Check className="mt-3" label={'Разрешить смотреть список неправильных ответов после теста'}/>
                        <Form.Check label={'Сделать все результаты прохождения публичными'}/>
                    </Form>
                    <h2 className="mt-4">Вопросы теста</h2>

                </Col>
                <Col className="col-sm-4">
                </Col>
            </Row>
        </Container>
    );
};

export default NewTest;
