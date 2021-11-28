import React, {FC, useEffect, useState} from 'react';
import {Col, Form, FormGroup, Row} from "react-bootstrap";
import {IQuestion, QuestionFormStore} from "./QuestionFormStore";
import AnswerForm from "./AnswerForm";
import {observer} from "mobx-react-lite";

interface QuestionFormItemProps {
    qKey?: number;
    question?: IQuestion;
    inputChange?: QuestionFormStore["inputChange"];
}

const QuestionFormItem: FC<QuestionFormItemProps> = observer(({qKey, question, inputChange}) => {

    useEffect(() => {
        inputChange(qKey, qKey + 1, 'position')
    }, [])

    function handleChange(e: React.ChangeEvent<HTMLInputElement>) {
        inputChange(qKey, e.target.value, e.target.name)
    }

    return (
        <div>
            <h5>Вопрос № {question.position}</h5>
            <Form className="mb-5">
                <Form.Group className="mb-3" controlId="formBasicEmail">
                    <Form.Control
                        name="questionText"
                        placeholder="Текст вопроса"
                        value={question.questionText}
                        onChange={handleChange}
                    />
                    <Form.Text className="text-muted">
                        Хорошо подумайте над текстом, что бы было понятно
                    </Form.Text>
                </Form.Group>
                <AnswerForm qKey={qKey}/>
                <Row className="align-items-center">
                    <Col xs="4">
                        <Form.Control
                            name="points"
                            value={question.points}
                            placeholder="Введите количество баллов за правильный ответ"
                        />
                    </Col>
                </Row>
            </Form>

        </div>
    );
})

export default QuestionFormItem;
